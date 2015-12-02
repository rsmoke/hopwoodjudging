function hasHtml5Validation () {
  return typeof document.createElement('input').checkValidity === 'function';
}

if (hasHtml5Validation()) {
  $('.validate-form').submit(function (e) {
    if (!this.checkValidity()) {
      e.preventDefault();
      $(this).addClass('invalid');
      $('#status').html('invalid');
    } else {
      $(this).removeClass('invalid');
      $('#status').html('submitted');
    }
  });
}


$( document ).ready(function(){
  $('#contest').on('click', '.btn-eval', function (e){
    var entryid = $(this).data('entryid');
    document.location = 'evaluation.php?evid=' + entryid;
  });

  $('.disabled').toggleClass("btn-info");

});