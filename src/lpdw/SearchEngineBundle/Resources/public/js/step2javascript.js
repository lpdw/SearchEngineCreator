$( document ).ready(function() {
    $('input, select').change(function(e) {
        var search_values = [];
        $("input[type!='hidden'], select").each(function() {
            search_values.push(
                    {
                        'type':$(this)[0].type,
                        'id':$(this).val(),
                        'checked':$(this)[0].checked,
                    }
            );
        });

        $.ajax({
            method: "post",
            url: "http://localhost:8000/searchEngine/" + window.location.pathname.split('/')[2] + "/getResults",
            data: {searchValues: search_values},
            success: function(data) {
                console.log(data);
            }
        });
    });
});
