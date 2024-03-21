@extends('layouts.admin')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
<div class="card">
  <div class="d-sm-flex align-items-center justify-content-between py-3">
  <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Edit Plan') }} <a class="btn btn-primary btn-rounded btn-sm" href="{{route('admin.loan.plan.index')}}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h5>
  <ol class="breadcrumb m-0 py-0">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
      <li class="breadcrumb-item"><a href="{{route('admin.loan.plan.index')}}">{{ __('Loan Plan') }}</a></li>
      <li class="breadcrumb-item"><a href="{{route('admin.loan.plan.edit',$data->id)}}">{{ __('Edit Plan') }}</a></li>
  </ol>
  </div>
</div>

<div class="row justify-content-center mt-3">
<div class="col-md-10">
  <div class="card mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">{{ __('Edit Plan Form') }}</h6>
    </div>

    <div class="card-body">
      <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
      <form class="geniusform" action="{{route('admin.loan.plan.update',$data->id)}}" method="POST" enctype="multipart/form-data">

          @include('includes.admin.form-both')

          {{ csrf_field() }}

          <div class="form-group">
            <label for="title">{{ __('Title') }}</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="{{ __('Enter Title') }}" value="{{ $data->title}}" required>
          </div>

          <div class="form-group">
            <label for="min_amount">{{ __('Minimum Price in') }} ({{$currency->name}})</label>
            <input type="number" class="form-control" id="min_amount" name="min_amount" placeholder="{{ __('Enter Minimum Price') }}" min="1" step="0.01" value="{{ $data->min_amount}}" required>
          </div>

          <div class="form-group">
            <label for="max_amount">{{ __('Maximum Price in') }} ({{$currency->name}})</label>
            <input type="number" class="form-control" id="max_amount" name="max_amount" placeholder="{{ __('Enter Maximum Price') }}" min="1" step="0.01" value="{{ $data->max_amount}}" required>
          </div>

          <div class="form-group">
            <label for="per_installment">{{ __('Per Installment') }} (%)</label>
            <input type="number" class="form-control" id="per_installment" name="per_installment" placeholder="{{ __('Per Installment') }}" min="1" step="0.01" value="{{ $data->per_installment}}" required>
          </div>

          <div class="form-group">
            <label for="installment_interval">{{ __('Installment Interval') }}</label>
            <input type="number" class="form-control" id="installment_interval" name="installment_interval" placeholder="{{ __('Installment Interval') }}" min="1" step="0.01" value="{{ $data->installment_interval }}" required>
          </div>

          <div class="form-group">
            <label for="total_installment">{{ __('Total Installment') }}</label>
            <input type="number" class="form-control" id="total_installment" name="total_installment" placeholder="{{ __('Total Installment') }}" min="1" value="{{ $data->total_installment}}" required>
          </div>
          
          <div class="form-group">
            <label for="type2">{{ __('Loan Sanction') }}</label> <br>
            <select id="type2" name="type2[]" multiple multiselect-select-all="true" class="form-control">
              <?php
              // Loop through the array to generate options
              foreach ($type2 as $value) {
                  $selected = in_array($value->id, explode(",", $data->loan_charges)) ? 'selected' : '';
                  echo "<option value='$value->id' $selected>$value->name</option>";
              }
                ?>
            </select>
          </div> 

          <div class="form-group">
            <label for="type3">{{ __('Fees Charges') }}</label> <br>
            <select id="type3" name="type3[]" multiple multiselect-select-all="true" class="form-control">
              <?php
              // Loop through the array to generate options
                foreach ($type3 as $value) {
                    $selected = in_array($value->id, explode(",", $data->loan_charges)) ? 'selected' : '';
                    echo "<option value='$value->id' $selected>$value->name</option>";
                }
                ?>
            </select>
          </div> 

          <div class="form-group">
            <label for="type4">{{ __('Annual Charges') }}</label> <br>
            <select id="type4" name="type4[]" multiple multiselect-select-all="true" class="form-control">
              <?php
              // Loop through the array to generate options
                foreach ($type4 as $value) {
                    $selected = in_array($value->id, explode(",", $data->loan_charges)) ? 'selected' : '';
                    echo "<option value='$value->id' $selected>$value->name</option>";
                }
                ?>
            </select>
          </div> 

          <div class="form-group">
            <label for="type7">{{ __('Miscellaneous Charges') }}</label> <br>
            <select id="type7" name="type7[]" multiple multiselect-select-all="true" class="form-control">
              <?php
              // Loop through the array to generate options
                foreach ($type7 as $value) {
                    $selected = in_array($value->id, explode(",", $data->loan_charges)) ? 'selected' : '';
                    echo "<option value='$value->id' $selected>$value->name</option>";
                }
                ?>
            </select>
          </div> 

          
          <div class="form-group">
            <h3 id="profitShow" class="text-center"></h3>
          </div>


          <div class="lang-tag-top-filds" id="lang-section">
            <label for="instruction">{{ __("Required Information") }}</label>
            @if (count($informations) != 0)
              @foreach ($informations as $key=>$info)
                <div class="lang-area mb-3">
                  <span class="remove lang-remove"><i class="fas fa-times"></i></span>
                  <div class="row">
                    <div class="col-md-6">
                      <input type="text" name="form_builder[{{ $key }}][field_name]" class="form-control" placeholder="{{ __('Field Name') }}" value="{{ $info['field_name'] }}">
                    </div>

                    <div class="col-md-3">
                      <select name="form_builder[{{ $key }}][type]" class="form-control">
                          <option value="text" {{ $info['type'] == 'text' ? 'selected' : '' }}> {{__('Input')}} </option>
                          <option value="textarea" {{ $info['type'] == 'textarea' ? 'selected' : '' }}> {{__('Textarea')}} </option>
                          <option value="file" {{ $info['type'] == 'file' ? 'selected' : '' }}> {{__('File upload')}} </option>
                      </select>
                    </div>

                    <div class="col-md-3">
                      <select name="form_builder[{{ $key }}][validation]" class="form-control">
                          <option value="required" {{ $info['validation'] == 'required' ? 'selected' : '' }}> {{__('Required')}} </option>
                          <option value="nullable" {{ $info['validation'] == 'nullable' ? 'selected' : '' }}>  {{__('Optional')}} </option>
                      </select>
                    </div>
                  </div>
                </div>
              @endforeach
            @endif


          </div>


          <a href="javascript:;" id="lang-btn" class="add-fild-btn d-flex justify-content-center"><i class="icofont-plus"></i> {{__('Add More Field')}}</a>

          <button type="submit" id="submit-btn" class="btn btn-primary w-100 mt-2">{{ __('Submit') }}</button>

      </form>
    </div>
  </div>
</div>

</div>

@endsection

@section('scripts')
<script type="text/javascript">
  'use strict';
  function isEmpty(el){
      return !$.trim(el.html())
  }

  let id = '{{count($informations) == 0 ? 1 : count($informations) + 1}}';

$("#lang-btn").on('click', function(){

    $("#lang-section").append(''+
            `<div class="lang-area mb-3">
            <span class="remove lang-remove"><i class="fas fa-times"></i></span>
            <div class="row">
              <div class="col-md-6">
                <input type="text" name="form_builder[${id}][field_name]" class="form-control" placeholder="{{ __('Field Name') }}">
              </div>

              <div class="col-md-3">
                <select name="form_builder[${id}][type]" class="form-control rounded-0">
                    <option value="text"> Input </option>
                    <option value="textarea"> Textarea </option>
                    <option value="file"> File upload </option>
                </select>
              </div>

              <div class="col-md-3">
                <select name="form_builder[${id}][validation]" class="form-control rounded-0">
                    <option value="required"> Required </option>
                    <option value="nullable">  Optional </option>
                </select>
              </div>
            </div>
          </div>`+
          '');
      id ++;
});

$(document).on('click','.lang-remove', function(){

    $(this.parentNode).remove();
    if(id && id >1){
      id --;
    }
    if (isEmpty($('#lang-section'))) {

      $("#lang-section").append(''+
            `<div class="lang-area mb-3">
            <span class="remove lang-remove"><i class="fas fa-times"></i></span>
            <div class="row">
              <div class="col-md-6">
                <input type="text" name="form_builder[1][field_name]" class="form-control" placeholder="{{ __('Field Name') }}">
              </div>

              <div class="col-md-3">
                <select name="form_builder[1][type]" class="form-control rounded-0">
                    <option value="text"> Input </option>
                    <option value="textarea"> Textarea </option>
                    <option value="file"> File upload </option>
                </select>
              </div>

              <div class="col-md-3">
                <select name="form_builder[1][validation]" class="form-control rounded-0">
                    <option value="required"> Required </option>
                    <option value="nullable">  Optional </option>
                </select>
              </div>
            </div>
          </div>`+
          '');
    }

});

$("#per_installment").on('input',()=>{
  profitCalculation();
})

$("#total_installment").on('input',()=>{
  profitCalculation();
})

function profitCalculation(){
  let perInstallment = parseFloat($("#per_installment").val());
  let totalInstallment = parseFloat($("#total_installment").val());

  if(perInstallment && totalInstallment){
    let profitLoss = (perInstallment * totalInstallment).toFixed(2);

    if(profitLoss>100){
      let profit = profitLoss - 100;
      $("#profitShow").text(`You will get ${profit} % profit`).removeClass('text-danger').addClass('text-success');
    }else if(profitLoss == 100){
      $("#profitShow").text(`You will get 0 % profit`).removeClass('text-danger').addClass('text-success');
    }else{
      let loss = 100 - profitLoss;
      $("#profitShow").text(`You will get ${loss} % loss`).removeClass('text-success').addClass('text-danger');
    }
  }
}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>

<script>
    var style = document.createElement('style');
style.setAttribute("id","multiselect_dropdown_styles");
style.innerHTML = `
.multiselect-dropdown{
  display: inline-block;
  padding: 2px 5px 0px 5px;
  border-radius: 4px;
  border: solid 1px #ced4da;
  background-color: white;
  position: relative;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right .75rem center;
  background-size: 16px 12px;
}
.multiselect-dropdown span.optext, .multiselect-dropdown span.placeholder{
  margin-right:0.5em; 
  margin-bottom:2px;
  padding:1px 0; 
  border-radius: 4px; 
  display:inline-block;
}
.multiselect-dropdown span.optext{
  background-color:lightgray;
  padding:1px 0.75em; 
}
.multiselect-dropdown span.optext .optdel {
  float: right;
  margin: 0 -6px 1px 5px;
  font-size: 0.7em;
  margin-top: 2px;
  cursor: pointer;
  color: #666;
}
.multiselect-dropdown span.optext .optdel:hover { color: #c66;}
.multiselect-dropdown span.placeholder{
  color:#ced4da;
}
.multiselect-dropdown-list-wrapper{
  box-shadow: gray 0 3px 8px;
  z-index: 100;
  padding:2px;
  border-radius: 4px;
  border: solid 1px #ced4da;
  display: none;
  margin: -1px;
  position: absolute;
  top:0;
  left: 0;
  right: 0;
  background: white;
}
.multiselect-dropdown-list-wrapper .multiselect-dropdown-search{
  margin-bottom:5px;
}
.multiselect-dropdown-list{
  padding:2px;
  height: 15rem;
  overflow-y:auto;
  overflow-x: hidden;
}
.multiselect-dropdown-list::-webkit-scrollbar {
  width: 6px;
}
.multiselect-dropdown-list::-webkit-scrollbar-thumb {
  background-color: #bec4ca;
  border-radius:3px;
}

.multiselect-dropdown-list div{
  padding: 5px;
}
.multiselect-dropdown-list input{
  height: 1.15em;
  width: 1.15em;
  margin-right: 0.35em;  
}
.multiselect-dropdown-list div.checked{
}
.multiselect-dropdown-list div:hover{
  background-color: #ced4da;
}
.multiselect-dropdown span.maxselected {width:100%;}
.multiselect-dropdown-all-selector {border-bottom:solid 1px #999;}
`;
document.head.appendChild(style);

function MultiselectDropdown(options){
  var config={
    search:true,
    height:'15rem',
    placeholder:'select',
    txtSelected:'selected',
    txtAll:'All',
    txtRemove: 'Remove',
    txtSearch:'search',
    ...options
  };
  function newEl(tag,attrs){
    var e=document.createElement(tag);
    if(attrs!==undefined) Object.keys(attrs).forEach(k=>{
      if(k==='class') { Array.isArray(attrs[k]) ? attrs[k].forEach(o=>o!==''?e.classList.add(o):0) : (attrs[k]!==''?e.classList.add(attrs[k]):0)}
      else if(k==='style'){  
        Object.keys(attrs[k]).forEach(ks=>{
          e.style[ks]=attrs[k][ks];
        });
       }
      else if(k==='text'){attrs[k]===''?e.innerHTML='&nbsp;':e.innerText=attrs[k]}
      else e[k]=attrs[k];
    });
    return e;
  }

  
  document.querySelectorAll("select[multiple]").forEach((el,k)=>{
    
    var div=newEl('div',{class:'multiselect-dropdown',style:{width:config.style?.width??el.clientWidth+'px',padding:config.style?.padding??''}});
    el.style.display='none';
    el.parentNode.insertBefore(div,el.nextSibling);
    var listWrap=newEl('div',{class:'multiselect-dropdown-list-wrapper'});
    var list=newEl('div',{class:'multiselect-dropdown-list',style:{height:config.height}});
    var search=newEl('input',{class:['multiselect-dropdown-search'].concat([config.searchInput?.class??'form-control']),style:{width:'100%',display:el.attributes['multiselect-search']?.value==='true'?'block':'none'},placeholder:config.txtSearch});
    listWrap.appendChild(search);
    div.appendChild(listWrap);
    listWrap.appendChild(list);

    el.loadOptions=()=>{
      list.innerHTML='';
      
      if(el.attributes['multiselect-select-all']?.value=='true'){
        var op=newEl('div',{class:'multiselect-dropdown-all-selector'})
        var ic=newEl('input',{type:'checkbox'});
        op.appendChild(ic);
        op.appendChild(newEl('label',{text:config.txtAll}));
  
        op.addEventListener('click',()=>{
          op.classList.toggle('checked');
          op.querySelector("input").checked=!op.querySelector("input").checked;
          
          var ch=op.querySelector("input").checked;
          list.querySelectorAll(":scope > div:not(.multiselect-dropdown-all-selector)")
            .forEach(i=>{if(i.style.display!=='none'){i.querySelector("input").checked=ch; i.optEl.selected=ch}});
  
          el.dispatchEvent(new Event('change'));
        });
        ic.addEventListener('click',(ev)=>{
          ic.checked=!ic.checked;
        });
        el.addEventListener('change', (ev)=>{
          let itms=Array.from(list.querySelectorAll(":scope > div:not(.multiselect-dropdown-all-selector)")).filter(e=>e.style.display!=='none')
          let existsNotSelected=itms.find(i=>!i.querySelector("input").checked);
          if(ic.checked && existsNotSelected) ic.checked=false;
          else if(ic.checked==false && existsNotSelected===undefined) ic.checked=true;
        });
  
        list.appendChild(op);
      }

      Array.from(el.options).map(o=>{
        var op=newEl('div',{class:o.selected?'checked':'',optEl:o})
        var ic=newEl('input',{type:'checkbox',checked:o.selected});
        op.appendChild(ic);
        op.appendChild(newEl('label',{text:o.text}));

        op.addEventListener('click',()=>{
          op.classList.toggle('checked');
          op.querySelector("input").checked=!op.querySelector("input").checked;
          op.optEl.selected=!!!op.optEl.selected;
          el.dispatchEvent(new Event('change'));
        });
        ic.addEventListener('click',(ev)=>{
          ic.checked=!ic.checked;
        });
        o.listitemEl=op;
        list.appendChild(op);
      });
      div.listEl=listWrap;

      div.refresh=()=>{
        div.querySelectorAll('span.optext, span.placeholder').forEach(t=>div.removeChild(t));
        var sels=Array.from(el.selectedOptions);
        if(sels.length>(el.attributes['multiselect-max-items']?.value??5)){
          div.appendChild(newEl('span',{class:['optext','maxselected'],text:sels.length+' '+config.txtSelected}));          
        }
        else{
          sels.map(x=>{
            var c=newEl('span',{class:'optext',text:x.text, srcOption: x});
            if((el.attributes['multiselect-hide-x']?.value !== 'true'))
              c.appendChild(newEl('span',{class:'optdel',text:'🗙',title:config.txtRemove, onclick:(ev)=>{c.srcOption.listitemEl.dispatchEvent(new Event('click'));div.refresh();ev.stopPropagation();}}));

            div.appendChild(c);
          });
        }
        if(0==el.selectedOptions.length) div.appendChild(newEl('span',{class:'placeholder',text:el.attributes['placeholder']?.value??config.placeholder}));
      };
      div.refresh();
    }
    el.loadOptions();
    
    search.addEventListener('input',()=>{
      list.querySelectorAll(":scope div:not(.multiselect-dropdown-all-selector)").forEach(d=>{
        var txt=d.querySelector("label").innerText.toUpperCase();
        d.style.display=txt.includes(search.value.toUpperCase())?'block':'none';
      });
    });

    div.addEventListener('click',()=>{
      div.listEl.style.display='block';
      search.focus();
      search.select();
    });
    
    document.addEventListener('click', function(event) {
      if (!div.contains(event.target)) {
        listWrap.style.display='none';
        div.refresh();
      }
    });    
  });
}

window.addEventListener('load',()=>{
  MultiselectDropdown(window.MultiselectDropdownOptions);
});
</script>
@endsection
