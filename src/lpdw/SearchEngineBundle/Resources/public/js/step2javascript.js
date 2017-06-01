$( document ).ready(function() {
    $('input, select').bind('keyup change', function(e) {
        var search_values = [];
        $("input[type!='hidden'], select").each(function() {
            if($(this).attr('id').includes('form_value4RangeType')) {
                if($(this).attr('id').includes('form_value4RangeType1')) {
                    search_values.push(
                            {
                                'type':$(this)[0].type,
                                'id':$(this).attr('class'),
                                'value':$(this).val() + '_' + $('#form_value4RangeType2' + $(this).attr('class')).val()
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
