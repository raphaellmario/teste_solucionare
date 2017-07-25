<?php

function open_form()
{
    return '<form method="post"><table>';
}

function input_field($nome, $descricao)
{
    $return  = '<tr>';
    $return .= '<td>' . $nome . '</td>';
    $return .= '<td><input type="text" name="' . $nome . '" /></td>';
    $return .= '<td>'.$descricao.'</td>';
    $return .= '</tr>';

    return $return;
}

function button_submit($nome = 'Submit', $valor = 1)
{
    return '<tr><td colspan="2"><button type="submit" value="'.$valor.'">'.$nome.'</button></td>';
}

function close_form()
{
    return '</table></form>';
}
