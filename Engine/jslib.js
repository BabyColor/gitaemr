$('.dropbtn').on('click', function(e) {
	e.preventDefault();
  
  // hide all
  $('.dropdown-content').hide();
  
  // show current dropdown
  $(this).next().show();
});