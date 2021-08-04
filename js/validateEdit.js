var maps_city  = '';
var address    = '';
var maps_state = '';
var lat        = '';
var lng        = '';

function initialize() {

  var input    = document.getElementById('searchTextField');
  var location = new google.maps.places.Autocomplete(input);
  var currAddr = jQuery('#searchTextField').val();

  location.addListener("place_changed", retrieveLocation);

  var geocoder = new google.maps.Geocoder();

  geocoder.geocode({
    'address' : currAddr
  }, function(results,status) {
    if (status === google.maps.GeocoderStatus.OK && results.length > 0) {

      for (let i = 0; i < results[0].address_components.length; i++) {
        if (results[0].address_components[i].types[0] == 'locality') {
          maps_city = results[0].address_components[i].long_name;
        }

        if (results[0].address_components[i].types[0] == 'administrative_area_level_1') {
          maps_state = results[0].address_components[i].long_name;
        }
      }

      lat = results[0].geometry.location.lat();
      lng = results[0].geometry.location.lng();

      address = jQuery('#searchTextField').val();

    } else alert("Invalid Address");  });

}   

 function togglePopup() {
    jQuery(".content").toggle();
}


window.addEventListener('load', (event) => {
  initialize();
})


function retrieveLocation() {

  var place = this.getPlace();

  for (let i = 0; i < place.address_components.length; i++) {
    if (place.address_components[i].types[0] == 'locality') {
      maps_city = place.address_components[i].long_name;
    }

    if (place.address_components[i].types[0] == 'administrative_area_level_1') {
      maps_state = place.address_components[i].long_name;
    }
  }

  lat = place.geometry.location.lat();
  lng = place.geometry.location.lng();

  address = jQuery('#searchTextField').val();

  // console.log(place);

}


function validateCustomPolicy() {

  if (jQuery('#customCanc').is('checked') === true) {
    return;
  } else {
    jQuery('#customDescrip').css('display','');
  }
}

function validateDefaultPolicy() {

  if (jQuery('#defaultCanc').is('checked') === false) {
    jQuery('#customDescrip').css('display','none');
  }

}

function requestRecord()  {

  //collect form information
  var user_id      = jQuery("#user_id").val();
  var tripName     = jQuery('#tripName').val();
  var description  = jQuery("#tripDescription").val();
  var duration     = jQuery('#duration').val();
  var maxPpl       = jQuery('#maxPpl').val().replace(/[^0-9\.]/g, '');
  var tripIncludes = jQuery('#tripIncludes').val();
  var pleaseBring  = jQuery('#pleaseBring').val();
  var price        = jQuery("#price").val().replace(/[^0-9\.]/g, '');
  var tripType     = jQuery('#tripType').val();
  var fullAddress  = address;
  var city         = maps_city;
  var state        = maps_state;
  var image        = jQuery('#uploadFile').prop('files')[0];
  var postID       = jQuery('#post_id').val();

  //declare form data
  var formData = new FormData();


  //price-validation
  //check if an integer, else throw error hint
  if (jQuery.isNumeric(price) === true) {
    jQuery("#priceErr").html('');
  } else {
    jQuery("#priceErr").html('Invalid number.');
  }

  //cancellation policy
  if (jQuery('#defaultCanc:checked').length === 1) {
    var cancPolicy = 'default';
  } else {
    var cancPolicy = 'custom';
    var cancDescription = jQuery('#customDescrip').val();
  }

  //check if image has been attached.  if there is no image, check to see if there is an existing image, if so. just pass the existing address/filename.
  //if there is a file, append to form data to send to php
  if (jQuery('#uploadFile').get(0).files.length === 0 && !jQuery('#imgSrc').length) {
      image = '';
  } else if (jQuery('#uploadFile').get(0).files.length === 0) {
      currImg = document.getElementById('imgSrc');
      var src = currImg.src.split('/');
      var filename = src[src.length-1];
  } else {
      formData.append('file', image);
  }

  //check if any of the fields are empty, if they are, send message and halt process.
  const keys = [

  {'field': 'user_id'     , 'value': user_id},
  {'field': 'tripName'    , 'value': tripName},
  {'field': 'description' , 'value': description},
  {'field': 'duration'    , 'value': duration},
  {'field': 'maxPpl'      , 'value': maxPpl},
  {'field': 'tripIncludes', 'value': tripIncludes},
  {'field': 'pleaseBring' , 'value': pleaseBring},
  {'field': 'price'       , 'value': price},
  {'field': 'tripType'    , 'value': tripType},
  {'field': 'fullAddress' , 'value': fullAddress},
  {'field': 'image'       , 'value': image}

  ];

  var empty = new Array;

  //reomve pre-existing error messages if the captain has filled it in
  for (let i = 0; i < keys.length; i++) {
    if (keys[i].value != '') {
      jQuery('#' + keys[i].field + 'Err').html('');
    }
  }

  //if we find any empty values, add them to the empty array to spit out error message all together
  for (let i = 0; i < keys.length; i++) {
    if (keys[i].value === '') {
      empty.push(keys[i].field);
    }
  }

  //check the empty array, if its empty, submit form.
  //if everything is good, add everything to the form data object
  if (empty.length === 0) {
    //create formData array
    let fd = [
      {'field': 'user_id'     , 'value': user_id},
      {'field': 'tripName'    , 'value': tripName},
      {'field': 'description' , 'value': description},
      {'field': 'duration'    , 'value': duration},
      {'field': 'maxPpl'      , 'value': maxPpl},
      {'field': 'tripIncludes', 'value': tripIncludes},
      {'field': 'pleaseBring' , 'value': pleaseBring},
      {'field': 'price'       , 'value': price},
      {'field': 'tripType'    , 'value': tripType},
      {'field': 'fullAddress' , 'value': fullAddress},
      {'field': 'lng'         , 'value': lng},
      {'field': 'lat'         , 'value': lat},
      {'field': 'cancPolicy'  , 'value': cancPolicy},
      {'field': 'address'     , 'value': fullAddress},
      {'field': 'city'        , 'value': city},
      {'field': 'state'       , 'value': state},
      {'field': 'filename'    , 'value': filename},
      {'field': 'post_id'     , 'value': postID},
      {'field': 'state'       , 'value': state},
      {'field': 'city'        , 'value': city}
    ];

    //append all the needed variables into the formdata object
    for (let i = 0; i < fd.length; i++) {
      formData.append(fd[i].field, fd[i].value);
    }

    // // debugging
    // for (var pair of formData.entries()) {
    //     console.log(pair[0]+ ', ' + pair[1]); 
    // }


    jQuery.ajax({
      type: "POST",
      url: "../wp-content/themes/traveler/st_templates/user/verify/validateEdit.php",
      enctype: 'multipart/form-data',
      contentType: false,
      processData: false,
      data: formData,
      dataType: "html"

    }).done(function( result ) {

      if (result == 0) {
        jQuery("#tripStatus").html('Nothing to update!');
      } else if (result == 1) {
        togglePopup();
        jQuery("#tripStatus").html('Successfully updated!');
      } else {
        jQuery("#tripStatus").html(result);
      }

  });

  } else {
  //if the array is not empty,send error messages for captains to correct
    for (let i = 0; i < empty.length; i++) {
      if (empty[i] === 'price' || empty[i] === 'maxPpl') {
        
        jQuery('#' + empty[i] + 'Err').html('This field is required and must be a number.');

      } else {

        jQuery('#' + empty[i] + 'Err').html('This field is required.');

      }
    }

    return;
  }

}