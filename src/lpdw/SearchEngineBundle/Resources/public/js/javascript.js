$( document ).ready(function() {
  type = $("#lpdw_searchenginebundle_feature_type").val();
  i = 1;

  if(type=="select"){
    $('#addInput').html(
      '<label for="select_'+i+'">Value'+i+'</label>'+
      '<input type="text" name="input_select_'+i+'" id="select_'+i+'"><br>'+
      '<button id="add_select" type="button" onClick="addSelect()">+</button><br>'
    );
    $('#edit_value').before(
      '<button id="add_select" type="button" onClick="addSelect()">+</button><br>'
    )
  }
  else if (type=="checkbox") {
    $('#addInput').html(
      '<label for="checkbox_'+i+'">Value'+i+'</label>'+
      '<input type="text" name="input_checkbox_'+i+'" id="checkbox_'+i+'"><br>'+
      '<label for="checkbox_comment_'+i+'">Commentaire '+i+'</label>'+
      '<textarea name="comment_checkbox_'+i+'" id="checkbox_comment_'+i+'"></textarea><br>'+
      '<br><label for="checkbox_image_'+i+'">Image '+i+'</label>'+
      '<input type="file" name="image_checkbox_'+i+'" id="checkbox_image_'+i+'"><br>'+
      '<button id="add_select" type="button" onClick="addCheckbox()">+</button><br>'
    );
    $('#edit_value').before(
      '<button id="add_select" type="button" onClick="addCheckbox()">+</button><br>'
    )
  }
  else if (type=="radio") {
    $('#addInput').html(
      '<label for="radio_1">Valeur 1</label>'+
      '<input type="text" name="input_radio_1" id="radio_1" ><br>'+
      '<label for="radio_2">Valeur 2</label>'+
      '<input type="text" name="input_radio_2" id="radio_2" >'
    );
  }
  else if (type=="TextType") {
    $('#addInput').html(
      '<label for="text">Valeur</label>'+
      '<input type="text" name="input_text" id="text" >'
    );
  }
  else if (type=="NumberType") {
    $('#addInput').html(
      '<label for="number">Valeur</label>'+
      '<input type="number" name="input_number" id="number" >'
    );
  }
  else if (type=="RangeType") {
    $('#addInput').html(
      '<label for="min">min</label>'+
      '<input type="text" name="input_min" id="min" >'+
      ' - '
      +'<label for="max">max</label>'+
      '<input type="text" name="input_max" id="max" >'
    );
  }
  else if (type=="BooleanType") {
    $('#addInput').html(
      '<label for="boolean">Valeur</label>'+
      '<input type="text" name="input_boolean" id="boolean" >'
    );
  }


  $("#lpdw_searchenginebundle_feature_type").change(function(){
    i=1;

    if($(this).val()=="select"){
      $('#addInput').html(
        '<label for="select_'+i+'">Value'+i+'</label>'+
        '<input type="text" name="input_select_'+i+'" id="select_'+i+'"><br>'+
        '<button id="add_select" type="button" onClick="addSelect()">+</button><br>'
      );
      $('#addInputEdit').html(
        '<label for="select_'+i+'">Value'+i+'</label>'+
        '<input type="text" name="input_select_'+i+'" id="select_'+i+'"><br>'+
        '<button id="add_select" type="button" onClick="addSelect()">+</button><br>'+
        '<input type="submit" value="Edit" id="edit_value"/>'
      );
    }
    else if($(this).val()=="checkbox"){
      $('#addInput').html(
        '<label for="checkbox_'+i+'">Value'+i+'</label>'+
        '<input type="text" name="input_checkbox_'+i+'" id="checkbox_'+i+'"><br>'+
        '<label for="checkbox_comment_'+i+'">Commentaire '+i+'</label>'+
        '<textarea name="comment_checkbox_'+i+'" id="checkbox_comment_'+i+'"></textarea><br>'+
        '<br><label for="checkbox_image_'+i+'">Image '+i+'</label>'+
        '<input type="file" name="image_checkbox_'+i+'" id="checkbox_image_'+i+'"><br>'+
        '<button id="add_select" type="button" onClick="addCheckbox()">+</button><br>'
      );
      $('#addInputEdit').html(
        '<label for="checkbox_'+i+'">Value'+i+'</label>'+
        '<input type="text" name="input_checkbox_'+i+'" id="checkbox_'+i+'"><br>'+
        '<label for="checkbox_comment_'+i+'">Commentaire '+i+'</label>'+
        '<textarea name="comment_checkbox_'+i+'" id="checkbox_comment_'+i+'"></textarea><br>'+
        '<br><label for="checkbox_image_'+i+'">Image '+i+'</label>'+
        '<input type="file" name="image_checkbox_'+i+'" id="checkbox_image_'+i+'"><br>'+
        '<button id="add_select" type="button" onClick="addCheckbox()">+</button><br>'+
        '<input type="submit" value="Edit" id="edit_value"/>'
      );
    }
    else if ($(this).val()=="radio") {
      $('#addInput').html(
        '<label for="radio_1">Valeur 1</label>'+
        '<input type="text" name="input_radio_1" id="radio_1" ><br>'+
        '<label for="radio_2">Valeur 2</label>'+
        '<input type="text" name="input_radio_2" id="radio_2" >'
      );
      $('#addInputEdit').html(
        '<label for="radio_1">Valeur 1</label>'+
        '<input type="text" name="input_radio_1" id="radio_1" ><br>'+
        '<label for="radio_2">Valeur 2</label>'+
        '<input type="text" name="input_radio_2" id="radio_2" >'+
        '<input type="submit" value="Edit" id="edit_value"/>'
      );
    }
    else if ($(this).val()=="TextType") {
      $('#addInput').html(
        '<label for="text">Valeur</label>'+
        '<input type="text" name="input_text" id="text" >'
      );
      $('#addInputEdit').html(
        '<label for="text">Valeur</label>'+
        '<input type="text" name="input_text" id="text" >'+
        '<input type="submit" value="Edit" id="edit_value"/>'
      );
    }
    else if ($(this).val()=="NumberType") {
      $('#addInput').html(
        '<label for="number">Valeur</label>'+
        '<input type="number" name="input_number" id="number" >'
      );
      $('#addInputEdit').html(
        '<label for="number">Valeur</label>'+
        '<input type="number" name="input_number" id="number" >'+
        '<input type="submit" value="Edit" id="edit_value"/>'
      );
    }
    else if ($(this).val()=="RangeType") {
      $('#addInput').html(
        '<label for="min">min</label>'+
        '<input type="text" name="input_min" id="min" >'+
        ' - '
        +'<label for="max">max</label>'+
        '<input type="text" name="input_max" id="max" >'
      );
      $('#addInputEdit').html(
        '<label for="min">min</label>'+
        '<input type="text" name="input_min" id="min" >'+
        ' - '
        +'<label for="max">max</label>'+
        '<input type="text" name="input_max" id="max" >'+
        '<input type="submit" value="Edit" id="edit_value"/>'
      );
    }
    else if ($(this).val()=="BooleanType") {
      $('#addInput').html(
        '<label for="boolean">Valeur</label>'+
        '<input type="text" name="input_boolean" id="boolean" >'
      );
      $('#addInputEdit').html(
        '<label for="boolean">Valeur</label>'+
        '<input type="text" name="input_boolean" id="boolean" >'+
        '<input type="submit" value="Edit" id="edit_value"/>'
      );
    }
  })

});

function addSelect(){
  old_i = $("#form > div").length;
  new_i = $("#addInputEdit > input").length;
  if($("#addInput > input").length!=0){
    input_i = $("#addInput > input").length + 1;
  }
  else{
    input_i=0;
  }
  i = old_i + new_i + input_i;
  $('#add_select').before(
    '<label for="select_'+i+'">Value'+i+'</label>'+
    '<input type="text" name="input_select_'+i+'" id="select_'+i+'"><br>'
  );
}

function addCheckbox(){
  old_i = $("#form > div").length;
  new_i = $("#addInputEdit > input").length;
  if($("#addInput > input").length!=0){
    input_i = $("#addInput > input").length + 1;
  }
  else{
    input_i=0;
  }
  i = Math.ceil((old_i + new_i + input_i)/2);
  $('#add_select').before(
    '<label for="checkbox_'+i+'">Value'+i+'</label>'+
    '<input type="text" name="input_checkbox_'+i+'" id="checkbox_'+i+'">'+
    '<br><label for="checkbox_comment_'+i+'">Commentaire '+i+'</label>'+
    '<textarea name="comment_checkbox_'+i+'" id="checkbox_comment_'+i+'"></textarea>'+
    '<br><label for="checkbox_image_'+i+'">Image '+i+'</label>'+
    '<input type="file" name="image_checkbox_'+i+'" id="checkbox_image_'+i+'"><br>'
  );
}
