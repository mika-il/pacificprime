$(document).ready( function() {

  $('form').submit( function(event) {
    event.preventDefault();
    //remove the errors
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
 
  
    //remove success messages
    $('#messages').removeClass('alert alert-success').empty();

    var formData = new FormData(this);
    formData.append('file',$('#companyLogo')[0].files[0]);
    // process the form
    $.ajax({
      type: 'POST',
      url: 'process.php',
      data: formData,
      contentType: false,
      cache: false,
      processData:false,
      dataType: 'json',
      beforeSend: function() {
         $('#buttonSubmit').attr('disabled', true);
      },
      success: function(data) {
        $('#buttonSubmit').removeAttr('disabled');
        if (!data.success) {
          if (data.errors.name) {
            $('#name-group').addClass('has-error');
            $('#name-group .help-block').html(data.errors.name);
          }

          if (data.errors.phone) {
            $('#phone-group').addClass('has-error');
            $('#phone-group .help-block').html(data.errors.phone);
          }

          if (data.errors.email) {
            $('#email-group').addClass('has-error');
            $('#email-group .help-block').html(data.errors.email);
          }

          if (data.errors.company) {
            $('#company-group').addClass('has-error');
            $('#company-group .help-block').html(data.errors.company);
          }

          if (data.errors.companyLogo) {
            $('#companyLogo-group').addClass('has-error');
            $('#companyLogo-group .help-block').html(data.errors.companyLogo);
          }

        } else {
          $('#messages').addClass('alert alert-success').append('<p>' + data.message + '</p>');
          $('#customerName').append('Name: ' + data.result.name);
          $('#customerPhone').append('Phone: ' + data.result.phone);
          $('#customerEmail').append('Email: ' + data.result.email);
          $('#customerCompany').append('Company: ' + data.result.company);
          $('#customerCompanyLogo').append( '<img src="http://www.flybudgetlines.com/php/uploads/'+ data.result.companyLogo + '" class="img-thumbnail" >');
          $('#lastID').append('Last Insert ID: ' + data.result.lastID);
          $("#customerForm").trigger('reset');
        }
      }
    });

    
  });


});
