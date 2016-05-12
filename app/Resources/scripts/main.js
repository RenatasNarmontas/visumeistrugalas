$( document ).ready(function () {
    $(".reveal").on('click', function () {
        var $pwd = $(".pwd");
        console.log('called')
        if($pwd.val() != '') {

            if ($pwd.attr('type') === 'password') {
                $pwd.attr('type', 'text');
                $(this).text('Hide');
            } else {
                $pwd.attr('type', 'password');
                $(this).text('Show');
            }
        }
    });
})
