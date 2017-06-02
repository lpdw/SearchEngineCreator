$( document ).ready(function() {
    $('input, select').bind('keyup change', function(e) {
        var search_values = [];
        $("input[type!='hidden'], select").each(function() {
            if($(this).attr('id').includes('RangeType')) {
                if($(this).attr('id').includes('RangeType1')) {
                    let id2 = $(this).attr('id');
                    id2 = id2.replace('RangeType1', 'RangeType2');
                    search_values.push(
                            {
                                'type':$(this)[0].type,
                                'id':$(this).attr('class').split(' ')[0],
                                'value':$(this).val() + '_' + $('#' + id2).val()
                            }
                    );
                }
            } else {
                search_values.push(
                        {
                            'type':$(this)[0].type,
                            'id':$(this).val(),
                            'checked':$(this)[0].checked,
                        }
                );
            }
        });

        $.ajax({
            method: "get",
            url: "http://localhost:8000/searchEngine/" + window.location.pathname.split('/')[2] + "/getResults",
            data: {searchValues: search_values},
            success: function(data) {
                let results = [];
                let display = "<li class='white fontChampagne ft-25 resultsTitle'>Résultats</li>";
                let modal = "";
                if (data.length > 0) {
                    for(let i = 0; i < data.length; i++) {
                        results.push(JSON.parse(data[i]));
                        if (results[i].image) {
                            var zoomImg = "<i class='fa fa-search-plus' aria-hidden='true' data-toggle='modal' data-target='#"+results[i].id+"'></i>";
                            var img = "<img style='width: 100%; height: 100%; object-fit: cover;' src='/uploads/images/"+results[i].image+"' />";
                            modal +=
                            "<div class='modal fade' id='"+results[i].id+"' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>"+
                                "<div class='modal-dialog' role='document'>"+
                                    "<div class='modal-content'>"+
                                        "<div class='modal-body'>"+img+"</div>"+
                                        "<div class='modal-footer'>"+
                                            "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Fermer</button>"+
                                        "</div>"+
                                    "</div>"+
                                "</div>"+
                            "</div>";
                        } else {
                            var zoomImg = "";
                            var img = "<img style='width: 100%; height: 100%; object-fit: cover;' src='/bundles/lpdwsearchengine/images/no_image.png' />";
                        }
                        display += "<li><div style='margin-right: 10px; display: inline-block; width: 75px; height: 75px;'>" + img + "</div>" + results[i].name + zoomImg + " <span>" + results[i].matching + "</span></li>";
                        $(".modal_img").html(modal);
                        $(".results ul").html(display);
                    }
                } else {
                    $(".results ul").html("<li class='white fontChampagne ft-25 resultsTitle'>Pas de résultats :(</li>");
                }

                // console.log(results);
            }
        });
    });
});
