<?php


class Utils
{

    public  function inverteData($data){
        if(count(explode("/",$data)) > 1){
            return implode("-",array_reverse(explode("/",$data)));
        }elseif(count(explode("-",$data)) > 1){
            return implode("/",array_reverse(explode("-",$data)));
        }
    }

    public  function mascaraCpfCnpj($val)
    {
        $maskared = '';
        $k = 0;

        $cont = strlen($val);

        $mask = $cont == 11 ? '###.###.###-##' : '##.###.###/####-##';

        for($i = 0; $i<=strlen($mask)-1; $i++) {
            if($mask[$i] == '#') {
                if(isset($val[$k])) $maskared .= $val[$k++];
            } else {
                if(isset($mask[$i])) $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }

    public  function colocarDecimal($valorParcela)
    {
        if(!strstr($valorParcela,','))
        {
            $valorParcela = number_format($valorParcela,2,',','.');
        }

        $ex = explode(',',$valorParcela);
        $ex = array_reverse($ex);

        if( strlen(trim($ex[0])) == 1 )
        {
            $valorParcela = $valorParcela.'0';
        }

        return $valorParcela;
    }



}