$(document).on("focus", ".address", function(event) {
    $location_input = $(this);
    $location_input.attr("autocomplete", "nope");
    // $(".address").on("keydown", function(event) {
    //     if (event.keyCode == 8 || (event.keyCode == 46 && $(this).select())) {
    //         $location_input
    //             .parents(".autocomplete_box")
    //             .find(".city")
    //             .val("");
    //         $location_input
    //             .parents(".autocomplete_box")
    //             .find(".state")
    //             .val("");
    //         $location_input
    //             .parents(".autocomplete_box")
    //             .find(".zip_code")
    //             .val("");
    //     }
    // });

    var options = {
        /* componentRestrictions: {
			country: 'ca'
		} */
    };

    autocomplete = new google.maps.places.Autocomplete(
        $location_input.get(0),
        options
    );
    autocomplete.addListener("place_changed", fillInAddress);
    // $('.address').css('background','initial');
});

function fillInAddress() {
    var address = "";
    var route = "";
    var addressObj = "";
    var country = "";

    $location_input
        .parents(".autocomplete_box")
        .find(".city")
        .val("");
    $location_input
        .parents(".autocomplete_box")
        .find(".state")
        .val("");
    $location_input
        .parents(".autocomplete_box")
        .find(".zip_code")
        .val("");

    var result = autocomplete.getPlace();
    var lat = result.geometry.location.lat();
    var lng = result.geometry.location.lng();
    $location_input
        .parents(".autocomplete_box")
        .find(".lat")
        .val(lat);
    $location_input
        .parents(".autocomplete_box")
        .find(".lng")
        .val(lng);

    console.log(result.address_components);

    if (result.address_components != undefined) {
        for (var i = 0; i < result.address_components.length; i += 1) {
            addressObj = result.address_components[i];
            for (var j = 0; j < addressObj.types.length; j += 1) {
                // console.log(addressObj.types);
                if (addressObj.types[j] === "street_number") {
                    address += addressObj.long_name + " ";
                    // $location_input.parents('.autocomplete_box').find('.autocomplete_street_number').val(addressObj.long_name);
                }
                if (addressObj.types[j] === "route") {
                    address += addressObj.long_name;
                    // $location_input.parents('.autocomplete_box').find('.autocomplete_apt').val(addressObj.long_name);
                }
                if (addressObj.types[j] === "locality") {
                    $location_input
                        .parents(".autocomplete_box")
                        .find(".city")
                        .val(addressObj.long_name);
                    $location_input
                        .parents(".autocomplete_box")
                        .find(".city")
                        .removeClass("is-invalid");
                    $location_input
                        .parents(".autocomplete_box")
                        .find(".city-error")
                        .text("");
                } else if (
                    addressObj.types[j] === "administrative_area_level_1"
                ) {
                    $location_input
                        .parents(".autocomplete_box")
                        .find(".state")
                        .val(addressObj.long_name);
                    $location_input
                        .parents(".autocomplete_box")
                        .find(".state")
                        .removeClass("is-invalid");
                    $location_input
                        .parents(".autocomplete_box")
                        .find(".state-error")
                        .text("");
                }
                else if (addressObj.types[j] === 'country') {
                	country = addressObj.long_name;
                    $('#address_country').val(country);
                	// $location_input.parents('.autocomplete_box').find('.autocomplete_country').val(addressObj.long_name);
                	// $location_input.parents('.autocomplete_box').find('.autocomplete_country_code').val(addressObj.short_name);
                }
                else if (addressObj.types[j] === "postal_code") {
                    $location_input
                        .parents(".autocomplete_box")
                        .find(".zip_code")
                        .val(addressObj.long_name);
                    $location_input
                        .parents(".autocomplete_box")
                        .find(".zip_code")
                        .removeClass("is-invalid");
                    $location_input
                        .parents(".autocomplete_box")
                        .find(".zip_code-error")
                        .text("");
                }
            }
        }
        $location_input
            .parents(".autocomplete_box")
            .find(".address")
            .val(address);
        $location_input
            .parents(".autocomplete_box")
            .find(".address")
            .removeClass("is-invalid");
        $location_input
            .parents(".autocomplete_box")
            .find(".address-error")
            .text("");
    } else {
        console.log("Result not found");
        console.log(result);
    }
}
// // search by zipcode address
// $(document).on("focus", ".address_zipcode", function(event) {
//     console.log("code changes in zip code");
//     $location_input = $(this);
//     // $location_input.attr("address_zipcode", "nope");
//     var options = {
//         /* componentRestrictions: {
// 			country: 'ca'
// 		} */
//     };

//     autocomplete = new google.maps.places.Autocomplete(
//         $location_input.get(0),
//         options
//     );
//     autocomplete.addListener("place_changed", fillInZipcode);
//     // $('.address').css('background','initial');
// });
// function fillInZipcode()
// {
//     var address = "";
//     var route = "";
//     var addressObj = "";
//     var country = "";
//     var result = autocomplete.getPlace();
//    console.log(result.address_components);
//     if (result.address_components != undefined) {
//         for (var i = 0; i < result.address_components.length; i += 1) {
//             addressObj = result.address_components[i];
//             for (var j = 0; j < addressObj.types.length; j += 1) {
//                 // console.log(addressObj.types);
//                 if (addressObj.types[j] === "street_number") {
//                     address += addressObj.long_name + " ";
//                     // $location_input.parents('.autocomplete_box').find('.autocomplete_street_number').val(addressObj.long_name);
//                 }
//                 if (addressObj.types[j] === "route") {
//                     address += addressObj.long_name;
//                     // $location_input.parents('.autocomplete_box').find('.autocomplete_apt').val(addressObj.long_name);
//                 }
//                 $("#address_zipcode")
//                     .text("");
//                 if (addressObj.types[j] === "postal_code") {
//                     $("#address_zipcode")
//                     .text("");
//                     $("#address_zipcode")
//                         .val(addressObj.long_name);
//                 }
//             }
//         }
        
//     } else {
//         console.log("Result not found");
//         console.log(result);
//     }
// }
// /* Simple search on home page and search page */
