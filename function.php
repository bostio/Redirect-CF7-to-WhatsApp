<?php
add_filter( 'wpcf7_skip_mail', 'bostio_cf7_send_whatsapp', 10, 2 );
add_filter( 'wpcf7_display_message', 'bostio_cf_wa_sent_message', 10, 2 );
add_action( 'wp_footer', 'bostio_cf7_add_script_footer' );
function bostio_cf7_send_whatsapp( $skip_mail, $contact_form ) {
  	if( $contact_form->id() == 511 ) { // 511 = ID Contact Form
		$skip_mail = true;
	}
	return $skip_mail;
}

function bostio_cf_wa_sent_message( $message, $status ) {
  	if( $status == 'mail_sent_ok' ) {
		$message = 'Terima kasih telah mengisi kontak form';	  
	}
	return $message;  
}

function bostio_cf7_add_script_footer() {
  //if( is_page( 'kontak' ) ): // CF di halaman kontak
  if( true ): // CF di semua halaman
  ?><script>
  document.addEventListener( 'wpcf7mailsent', function( event ) {
	var inputs = event.detail.inputs;
	var the_text = '';
	jQuery.each( inputs, function( index, detail ) {
	  	if( the_text != '' ) { the_text += "\n"; }
		switch( detail.name ) {
		  case 'nama-anda': // sesuaikan dengan field CF7
			the_text += "Nama : " + detail.value;
			break;
		  case 'no-hp':
			the_text += "HP : " + detail.value;
			break;
		  case 'cemail-anda':
			the_text += "Email : " + detail.value;
			break;			
		  case 'isi-pesan':
			the_text += "Pesan : " + detail.value;
			break;
		}
	} );	
	the_text = window.encodeURIComponent( the_text );

    var the_phone = '6282114441263'; // ganti nomor dengan milik Anda
	var url = 'https://wa.me/' + the_phone + '&text=' + the_text;
	var isSafari = !!navigator.userAgent.match(/Version\/[\d\.]+.*Safari/);
    var iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
	
	if( isSafari && iOS ) {
		location = url;
	} else {
		window.open( url, '_blank' );
	}
  }, false );
  </script><?php  
  endif;
}
