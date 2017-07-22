$(document).ready(function() {	
	$('#datetimepicker1').datetimepicker({
        format: 'yyyy-mm-dd',
        minView: 'month',
        weekStart: 1,
        autoclose: false
    }).on('changeDate', function(ev){
        var d = new Date(ev.date);
        var day = $.fn.datetimepicker.dates['en'].days[d.getDay()];
        
        $('#standard').text('Använd som standard för alla ' + day.toLowerCase() + 'ar');

        // Fetch events for this day!
        $.ajax({
            url: '?controller=home&action=getEvent',
            data: {date: $('#date').val()},
            dataType: 'json'
        })
        .done(function(response) {
            console.log(response);
        });
    });

    $("#photo").change(function(){
        readURL(this);
    });
});

function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#photo-preview').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

