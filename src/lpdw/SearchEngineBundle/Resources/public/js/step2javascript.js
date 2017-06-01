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
                for(let i = 0; i < data.length; i++) {
                    results.push(JSON.parse(data[i]));
                }
                console.log(results);
            }
        });
    });
});
