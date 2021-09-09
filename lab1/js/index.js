$(function() {
    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }
    function validate_coordinate(coordinate){
        if($(coordinate).is(':checked')){
            return true;
        }
        else{
            return false;
        }
    }

    function validate_y() {
        const Y_MIN = -3;
        const Y_MAX = 3;
        let yField = $('#y_input');
        let numY = yField.val().replace(',', '.');

        if (isNumeric(numY) && numY >= Y_MIN && numY <= Y_MAX)
        {
            return true;
        } else {
            return false;
        }
    }

    function validateForm() {
        return validate_coordinate('.x_input') & validate_y() & validate_coordinate('.r_input');
    }

    $('#form').on('submit', function(event) {
        event.preventDefault();
        if (!validateForm()) return;
        $.ajax({
            url: 'php/index.php',
            method: 'POST',
            data: $(this).serialize() + '&timezone=' + new Date().getTimezoneOffset(),
            dataType: "json",
            success: function(data) {
                let answer;
                if (data.validate) {
                    answer = '<tr>';
                    answer += '<td>' + data.x_value + '</td>';
                    answer += '<td>' + data.y_value + '</td>';
                    answer += '<td>' + data.r_value + '</td>';
                    answer += '<td>' + data.current_time + '</td>';
                    answer += '<td>' + data.time_execute + '</td>';
                    answer += '<td>' + data.hit_result + '</td>';
                    $('#result_table').append(answer);
                }
            }
        });
    });
});
