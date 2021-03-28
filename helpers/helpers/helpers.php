<?php

function aldem_transform_text_p(string $text): string
{
    $textTransform = "";
    $arrayText = explode("-", $text);
    foreach ($arrayText as $string) {
        $textTransform .= "<p>$string</p>";
    }
    return $textTransform;
}



function aldem_show_message_custom($msg_success, $msg_act, $msg_error): void
{

    if (isset($_GET['errors'])) {
        echo '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>' . aldem_transform_text_p($_GET['errors']) . '</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
            ';
    }
    if (isset($_GET['msg'])) {


        if ($_GET['msg'] == '1') {
            echo '
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>' . $msg_success . '</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                ';
        } else if ($_GET['msg'] == '2') {
            echo '
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>' . $msg_act . '</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                ';
        } else {
            echo '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>' . $msg_error . '</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                ';
        }
    }
}

function aldem_set_action_name(string $nameAction): void
{
    echo '
    <input type="hidden" name="action_name" value="' . $nameAction . '">';
}
