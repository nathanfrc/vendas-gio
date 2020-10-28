<?php

/************************************* Funï¿½ï¿½es do framework ********************************************
01. Manipulaï¿½ï¿½o de strings
02. Validaï¿½ï¿½o de strings
03. Manipulaï¿½ï¿½o de Valores
04. Manippulaï¿½ï¿½o de Datas
05. Manippulaï¿½ï¿½o de Arquivos
06. Cï¿½lculo de Frete
07. Funï¿½ï¿½es relacionadas a envio e recebimento de e-mails
08. Funï¿½ï¿½es relacionadas a anï¿½lise de trï¿½fego 
09. Funï¿½ï¿½es nï¿½o categorizadas
************************************* Final do Sumï¿½rio de Funï¿½ï¿½es *************************************/

/************************************* Manipulaï¿½ï¿½o de strings *************************************/

// echo mascara($cnpj,'##.###.###/####-##');

namespace App\Lib\System;

class FunctionsFramework
{

function mascara($val, $mask) {
		$maskared = '';
		$k = 0;
		for($i = 0; $i<=strlen($mask)-1; $i++)
		{
		if($mask[$i] == '#')
		{
		if(isset($val[$k]))
		$maskared .= $val[$k++];
		}
		else
		{
		if(isset($mask[$i]))
		$maskared .= $mask[$i];
		}
		}
		return $maskared;
}


function maiusculas($str) {
//	return mb_strtoupper($str,'iso-8859-1');
	return strtoupper($str);
}

function minusculas($str) {
//	return mb_strtolower($str,'iso-8859-1');
	return strtolower($str);
}

// Gerar chaves randï¿½nicas
function randomkeys($length) {
	$key=NULL;
	$pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
	for($i=0;$i<$length;$i++) { $key .= $pattern{rand(0,35)}; }
	return $key;
}

// Retorna apenas os nï¿½meros de uma string
function onlynumbers($str)
{
	return preg_replace("/[^0-9]/", "", $str);
}


// Retirar caracteres especiais de uma url
function url($str)
{
	$str=strtr($str, "ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ _", "aaaaeeiooouucAAAAEEIOOOUUC--");
	$str=preg_replace("/[^a-zA-Z0-9-.]/", "", $str);
	$str=mb_strtolower($str,'iso-8859-1');
	return $str;
}

// Cria url amigï¿½vel
function url_amiga($on,$modulo,$go,$titulo) {
	if ($on=='true') { return $modulo.'_'.$go.'_'.url($titulo).'.html'; }
	else { return $modulo.'.php?go='.$go.'&file='.url($titulo).'.html'; }
}

// Retorna erro quando ele for string
function form_erro($str) {
	$return='<span class="erro"><strong>ERRO:</strong><br />'.nl2br($str).'</span>';
}

// Retorna erro quando ele for 
function retorna_erro($array) {
	$return='<span class="erro"><strong>ERRO:</strong>';
	for ($x=0;$x<sizeof($erro);$x++) { $return.='<li>'.$erro[$x].'</li>'; }
	$return.='</span>';
	return $return;
}


//Eemplo para retornar o decode: utf8_code_deep($dados, FALSE);
public  static function utf8_encode_array($dados)
{
	foreach ($dados as $key => $val) {
		$out[$key]=utf8_encode($val);
	}
	return $out;
	
	
	/*
	$array = array( 'nome' => 'Paiï¿½ï¿½o' , 'cidade' => 'Sï¿½o Paulo' );
	$array = array_map( 'htmlentities' , $array );
	
	//encode
	$json = html_entity_decode( json_encode( $array ) );
	
	//Output: {"nome":"Paiï¿½ï¿½o","cidade":"Sï¿½o Paulo"}
	echo $json;	
	*/
}


/************************************* Validaï¿½ï¿½o de strings *************************************/

// Validar um cnpj //
public static function valida_cnpj($cnpj)
{
	if (strlen($cnpj) <> 14) {return false; }
	$soma = 0;
	$soma += ($cnpj[0] * 5);$soma += ($cnpj[1] * 4);$soma += ($cnpj[2] * 3);$soma += ($cnpj[3] * 2);$soma += ($cnpj[4] * 9);$soma += ($cnpj[5] * 8);
	$soma += ($cnpj[6] * 7);$soma += ($cnpj[7] * 6);$soma += ($cnpj[8] * 5);$soma += ($cnpj[9] * 4);$soma += ($cnpj[10] * 3);$soma += ($cnpj[11] * 2);
	$d1 = $soma % 11;
	$d1 = $d1 < 2 ? 0 : 11 - $d1;
	$soma = 0;
	$soma += ($cnpj[0] * 6);$soma += ($cnpj[1] * 5);$soma += ($cnpj[2] * 4);$soma += ($cnpj[3] * 3);$soma += ($cnpj[4] * 2);$soma += ($cnpj[5] * 9);$soma += ($cnpj[6] * 8);
	$soma += ($cnpj[7] * 7);$soma += ($cnpj[8] * 6);$soma += ($cnpj[9] * 5);$soma += ($cnpj[10] * 4);$soma += ($cnpj[11] * 3);$soma += ($cnpj[12] * 2);
	$d2 = $soma % 11;
	$d2 = $d2 < 2 ? 0 : 11 - $d2;
	if ($cnpj[12] == $d1 && $cnpj[13] == $d2) {return true;} 
	else {return false;}
} 

// Validar um cpf //
public static function valida_cpf($cpf) {	
	// Verifiva se o nï¿½mero digitado contï¿½m todos os digitos
    $cpf = str_pad(@preg_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);
	// Verifica se nenhuma das sequï¿½ncias abaixo foi digitada, caso seja, retorna falso
    if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999')	{
		return false;
	}
	else {
		// Calcula os nï¿½meros para verificar se o CPF ï¿½ verdadeiro
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) { $d += $cpf{$c} * (($t + 1) - $c); }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf{$c} != $d) { return false; }
        }
        return true;
    }
}

// Validar um e-mail
function valida_email($email)
{
    if (function_exists('filter_var')) {
      if(filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
        return false;
      } else {
        return true;
      }
    } else {
		if (preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $email) == 1) { return true; }
		else { return false; }
    }
}

// Validar uma data
function validardata($date, $format = 'Y-m-d H:i:s') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

/************************************* Manipulaï¿½ï¿½o de Valores *************************************/

// Mostra um valor por extenso
function valor_extenso($valor = 0, $maiusculas = false) { 

	$singular = array("centavo", "real", "mil", "milhï¿½o", "bilhï¿½o", "trilhï¿½o", "quatrilhï¿½o"); 
	$plural = array("centavos", "reais", "mil", "milhï¿½es", "bilhï¿½es", "trilhï¿½es", "quatrilhï¿½es"); 
	
	$c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos"); 
	$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa"); 
	$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove"); 
	$u = array("", "um", "dois", "trï¿½s", "quatro", "cinco", "seis", "sete", "oito", "nove"); 
	
	$z = 0; 
	$rt = "";
	
	$valor = number_format($valor, 2, ".", "."); 
	$inteiro = explode(".", $valor); 
	for($i=0;$i<count($inteiro);$i++) 
	for($ii=strlen($inteiro[$i]);$ii<3;$ii++) 
	$inteiro[$i] = "0".$inteiro[$i]; 
	
	$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2); 
	for ($i=0;$i<count($inteiro);$i++) { 
		$valor = $inteiro[$i]; 
		$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]]; 
		$rd = ($valor[1] < 2) ? "" : $d[$valor[1]]; 
		$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : ""; 
		$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && $ru) ? " e " : "").$ru; 
		$t = count($inteiro)-1-$i; 
		$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : ""; 
		if ($valor == "000")$z++; elseif ($z > 0) $z--; 
		if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t]; 
		if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r; 
	} 
	
	if(!$maiusculas){ 
		return($rt ? $rt : "zero"); 
	} else { 
		if ($rt) $rt=ereg_replace(" E "," e ",ucwords($rt));
		return (($rt) ? ($rt) : "Zero"); 
	} 

}

/************************************* Manippulaï¿½ï¿½o de Datas *************************************/

// Valores para datas //
function timestamp_to_fulldate($var) {
	$mes['inteiro'] = array (	'01' => "janeiro",'02' => "fevereiro",'03' => "marco",'04' => "abril",'05' => "maio",'06' => "junho",
				  				'07' => "julho",'08' => "agosto",'09' => "setembro",'10' => "outubro",'11' => "novembro",'12' => "dezembro"
				 				);
	$mes['acron'] = array 	(	'01' => "jan",'02' => "fev",'03' => "mar",'04' => "abr",'05' => "mai",'06' => "jun",
				  				'07' => "jul",'08' => "ago",'09' => "set",'10' => "out",'11' => "nov",'12' => "dez"
				 				);
	
	$result = explode("-",eregi_replace(" ","-",$var));
	$data=array();
	$data[ano]=$result[0];
	$data[mes]=$result[1];
	$data[dia]=$result[2];
	$data[horario]=$result[3];
	$data[mes_int]=$mes['inteiro'][$data[mes]];
	$data[mes_acr]=$mes['acron'][$data[mes]];
	return($data);
}

function timestamp_to_date($var,$tipo) {
	if (strtotime($var)>0) { 
		return date($tipo, strtotime($var));
	}
	else {
		return false;
	}
}

// Incrementa dias, meses e anos na data //
function incrementar_data($entrada) {
	// controle de erros
	if (!isset($entrada['d'])) { $entrada['d']=0; }
	if (!isset($entrada['m'])) { $entrada['m']=0; }
	if (!isset($entrada['y'])) { $entrada['y']=0; }
	if (!isset($entrada['type'])) { $entrada['type']='+'; }
	if (!isset($entrada['data'])) { $timestamp=time(); } else { $timestamp=strtotime($entrada['data']); }
	// retorno da funï¿½ï¿½o
	if ($entrada['type']=='+') {
		return date("Y-m-d H:i:s", mktime(date("h",$timestamp),date("m",$timestamp),date("s",$timestamp),date("m",$timestamp)+$entrada['m'],date("d",$timestamp)+$entrada['d'],date("y",$timestamp)+$entrada['y']));
	} else {
		return date("Y-m-d H:i:s", mktime(date("h",$timestamp),date("m",$timestamp),date("s",$timestamp),date("m",$timestamp)-$entrada['m'],date("d",$timestamp)-$entrada['d'],date("y",$timestamp)-$entrada['y']));
	}
}

// Data por extenso //
function data_extenso($data){
	$nova = explode(" ", $data);
	$nova = explode("-", $nova[0]);
	$vardia = $nova[2];
	$varmes = $nova[1];
	$varano = $nova[0];
	$convertedia = date ("w", mktime(0, 0, 0, $varmes, $vardia, $varano));
	$diasemana = array("Domingo", "Segunda-Feira", "Ter&ccedil;a-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "S&aacute;bado");
	$mes = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Mar&ccedil;o", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");
	return $vardia  . " de " . $mes[$varmes] . " de " . $varano ."";
}



function dias_uteis($mes,$ano){
  
  $uteis = 0;
  // Obtï¿½m o nï¿½mero de dias no mï¿½s 
  // (http://php.net/manual/en/function.cal-days-in-month.php)
  $dias_no_mes = $num = cal_days_in_month(CAL_GREGORIAN, $mes, $ano); 

  for($dia = 1; $dia <= $dias_no_mes; $dia++){

    // Aqui vocï¿½ pode verifica se tem feriado
    // ----------------------------------------
    // Obtï¿½m o timestamp
    // (http://php.net/manual/pt_BR/function.mktime.php)
    $timestamp = mktime(0, 0, 0, $mes, $dia, $ano);
    $semana    = date("N", $timestamp);

    if($semana < 6) $uteis++;

  }

  return $uteis;

}



//CALCULANDO DIAS NORMAIS
/*Abaixo vamos calcular a diferenï¿½a entre duas datas. Fazemos uma reversï¿½o da maior sobre a menor 
para nï¿½o termos um resultado negativo. */
function calcula_dias_corridos($xDataInicial, $xDataFinal){
   $time1 = date_to_timestamp($xDataInicial);  
   $time2 = date_to_timestamp($xDataFinal);  

   $tMaior = $time1>$time2 ? $time1 : $time2;  
   $tMenor = $time1<$time2 ? $time1 : $time2;  

   $diff = $tMaior-$tMenor;  
   $numDias = $diff/86400; //86400 ï¿½ o nï¿½mero de segundos que 1 dia possui  
   return $numDias;
}

//LISTA DE retorna_feriados NO ANO
/*Abaixo criamos um array para registrar todos os retorna_feriados existentes durante o ano.*/
function retorna_feriados($ano,$posicao){
	$dia = 86400;
	$datas = array();
	$datas['pascoa'] = easter_date($ano);
	$datas['sexta_santa'] = $datas['pascoa'] - (2 * $dia);
	$datas['carnaval'] = $datas['pascoa'] - (47 * $dia);
	$datas['corpus_cristi'] = $datas['pascoa'] + (60 * $dia);
	
	$retorna_feriados[] = '01/01';
	$retorna_feriados[] = '02/02';
	$retorna_feriados[] = date('d/m',$datas['carnaval']);
	$retorna_feriados[] = date('d/m',$datas['sexta_santa']);
	$retorna_feriados[] = date('d/m',$datas['pascoa']);
	$retorna_feriados[] = '21/04';
	$retorna_feriados[] = '01/05';
	$retorna_feriados[] = date('d/m',$datas['corpus_cristi']);
	$retorna_feriados[] = '12/10';
	$retorna_feriados[] = '02/11';
	$retorna_feriados[] = '15/11';
	$retorna_feriados[] = '25/12';

	return $retorna_feriados[$posicao]."/".$ano;
}      

//FORMATA COMO TIMESTAMP
/*Esta funï¿½ï¿½o ï¿½ bem simples, e foi criada somente para nos ajudar a formatar a data jï¿½ em formato  TimeStamp facilitando nossa soma de dias para uma data qualquer.*/
function date_to_timestamp($data){
   $ano = substr($data, 6,4);
   $mes = substr($data, 3,2);
   $dia = substr($data, 0,2);
return mktime(0, 0, 0, $mes, $dia, $ano);  
} 

//SOMA 01 DIA   
function soma_01_dia($data){   
   $ano = substr($data, 6,4);
   $mes = substr($data, 3,2);
   $dia = substr($data, 0,2);
return   date("d/m/Y", mktime(0, 0, 0, $mes, $dia+1, $ano));
}


//CALCULA DIAS UTEIS
/*ï¿½ nesta funï¿½ï¿½o que faremos o calculo. Abaixo podemos ver que faremos o cï¿½lculo normal de dias ($calculo_dias), apï¿½s este cï¿½lculo, faremos a comparaï¿½ï¿½o de dia a dia, verificando se este dia ï¿½ um sï¿½bado, domingo ou feriado e em qualquer destas condiï¿½ï¿½es iremos incrementar 1*/

function calcula_dias_uteis($data_inicial,$data_final){

   $dia_fds = 0; //dias nï¿½o ï¿½teis(Sï¿½bado=6 Domingo=0)
   $calculo_dias = calcula_dias_corridos($data_inicial, $data_final); //nï¿½mero de dias entre a data inicial e a final
   $calcula_dias_uteis = 0;
   
   while($data_inicial!=$data_final){
      $dia_semana = date("w", date_to_timestamp($data_inicial));
      if($dia_semana==0 || $dia_semana==6){
         //se SABADO OU DOMINGO, SOMA 01
         $dia_fds++;
      }else{
      //senï¿½o vemos se este dia ï¿½ FERIADO
         for($i=0; $i<=12; $i++){
            if($data_inicial==retorna_feriados(date("Y"),$i)){
               $dia_fds++;   
            }
         }
      }
      $data_inicial = soma_01_dia($data_inicial); //dia + 1
   }
return $calculo_dias - $dia_fds;
}


/*
   $inicio 	= "18/10/2010";
   $final 	= "27/10/2010";
   
   //CHAMADA DA FUNCAO
   echo calcula_dias_uteis($inicio, $final);
   echo calcula_dias_corridos($inicio, $final);
/*

/************************************* Manippulaï¿½ï¿½o de Arquivos *************************************/


// Pega lista de arquivos presente em um diretï¿½rio //
function get_files($dir,$args) {
	$arquivo = array();
	if (is_dir($dir)) {
		$dh = opendir($dir);
		// loop que busca todos os arquivos at&eacute; que n&atilde;o encontre mais nada
		while (false !== ($filename = readdir($dh))) {
			if (eregi($args, $filename)) {
				$i++;
				$arquivo[]=$filename;
			}
		}
	}
	return $arquivo;
}
// Exemplo de utilizaï¿½ï¿½o: print_r(get_files('/images/dir','jpeg|jpg|bmp|png'));



// Cria diretï¿½rio com index //
function cria_dir($dir) {
	if (!is_dir($dir)) {
		mkdir("$dir", 0777);
		chmod ("$dir", 0777);
		$fp = fopen("$dir".'/index.php', 'w');
		fwrite($fp, "<?php header('Location: /'); ?>");
		chmod ("$dir".'/index.php', 0644);
		fclose($fp);
	}
}


// Funï¿½ï¿½o que permite deletar um diretï¿½rio com vï¿½rios arquivos //
function deldir($f) {
  if (is_dir($f)) {
    foreach(glob($f.'/*') as $sf) {
      if (is_dir($sf) && !is_link($sf)) {
        deltree($sf);
      } else {
        unlink($sf);
      } 
    } 
  }
  rmdir($f);
}


// Cria Thumbnails
function criar_thumbnail($origem,$largura,$destino) { 
	$im = imagecreatefromjpeg($origem); 
	$w = imagesx($im); 
	$h = imagesy($im); 

	if ($w > $h) { $nw = $largura; $nh = ($h * $largura)/$w; }
	else{ $nh = $largura; $nw = ($w * $largura)/$h; } 
	$ni = imageCreateTrueColor($nw,$nh); 
	imagecopyresized($ni,$im,0,0,0,0,$nw,$nh,$w,$h);
	imagejpeg($ni,$destino,100); 
	if (is_file($destino)) { chmod ($destino, 0666); return true; }
	else { return false; }
} 

// Upload de arquivos
// $arquivo vai ser o arquivo em si
// $dados serï¿½o os dados para upload como restriï¿½ï¿½o de arquivos [tipo_arquivo] e o destino do mesmo [destino]
function fileupload($arquivo,$dados) {
	if ($permitido = true) {
		// Primeiro verifica se estï¿½ setado o destino do arquivo, se ele tem a barra do final e se ele ï¿½ um diretï¿½rio
		if (substr($dados['destino'],-1)<>'/') {$dados['destino']=$dados['destino'].'/'; }
		// Caso seja um diretï¿½rio passa para a criaï¿½ï¿½o do destino completo com o nome do arquivo
		if (is_dir($dados['destino'])) { 
			// Case esteja setado o nome do arquivo, modifica o destino final para esse nome
			if (isset($dados['filename'])) { $uploadfile = $dados['destino'] . url($dados['filename']); }
			// Caso contrï¿½rio coloca o nome do arquivo utilizando a funï¿½ï¿½o de url
			else { $uploadfile = $dados['destino'] . url($_FILES['arquivo']['name']); }
			// Move o arquivo
			if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile)) {
				// chmod no arquivo criado
				if (is_file($uploadfile)) { chmod ($uploadfile, 0666); }
				return $dados;
			} else {
				return 'Nï¿½o foi possï¿½vel mover o arquivo para o diretï¿½rio especificado';
			}
		// Caso nï¿½o seja um diretï¿½rio o destino retorna erro de diretï¿½rio
		} else {
			return 'Diretï¿½rio nï¿½o encontrado';
		}
	} else {
		return 'Arquivo nï¿½o permitido';
	}
}


// Upload de imagens //
// Precisa melhorar muito para ficar aqui //
function imgupload() {
	global $arquivo_diretorio,$imgupload,$arquivo_nome,$modulo_diretorio;
	$arquivo=explode('.',$imgupload['name']);
	if ($arquivo_nome <> 'capa') {
		// tratando nome do arquivo
		$arquivo_nome=strtolower(accents($arquivo[0])) . "-" . randomkeys(6);
	}
	
	// pegando tamanho do array para saber onde estï¿½ a extensï¿½o
	$arquivo_size=sizeof($arquivo); 
	--$arquivo_size; 
	$arquivo_ext=strtolower($arquivo[$arquivo_size]);

	if (($arquivo_ext == "jpg") || ($arquivo_ext == "jpeg") || ($arquivo_ext == "png") || ($arquivo_ext == "gif") || ($arquivo_ext == "bmp")) {
		$dest= '../files/' . $modulo_diretorio . '/' . $arquivo_diretorio . '/' . $arquivo_nome . '.' . $arquivo_ext;
		move_uploaded_file($imgupload['tmp_name'], $dest);
		chmod($dest,0666);
		$arquivo = "../files/" . "$modulo_diretorio" . "/" . "$arquivo_diretorio" . "/" . "$arquivo_nome" . "." . "$arquivo_ext";
		return "$arquivo";
	}
}




/************************************* Cï¿½lculo de Frete *************************************/



function calculaFrete($cod_servico, $cep_origem, $cep_destino, $peso, $altura='2', $largura='11', $comprimento='16', $valor_declarado='0.50')
{
    #OFICINADANET###############################
    # Cï¿½digo dos Serviï¿½os dos Correios
    # 41106 PAC sem contrato
    # 40010 SEDEX sem contrato
    # 40045 SEDEX a Cobrar, sem contrato
    # 40215 SEDEX 10, sem contrato
    ############################################

    $correios = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=&sDsSenha=&sCepOrigem=".$cep_origem."&sCepDestino=".$cep_destino."&nVlPeso=".$peso."&nCdFormato=1&nVlComprimento=".$comprimento."&nVlAltura=".$altura."&nVlLargura=".$largura."&sCdMaoPropria=n&nVlValorDeclarado=".$valor_declarado."&sCdAvisoRecebimento=n&nCdServico=".$cod_servico."&nVlDiametro=0&StrRetorno=xml";
    $xml = simplexml_load_file($correios);
    if($xml->cServico->Erro == '0')
        return $xml->cServico->Valor;
    else
        return false;
}

/*
echo "<br><Br>Cï¿½lculo de FRETE PAC: ". 
calculaFrete('41106','26255170','96825150','0.1')."<br>";

echo "<br><Br>Cï¿½lculo de FRETE SEDEX: ". 
calculaFrete('40010','26255170','96825150','0.1')."<br>";

echo "<br><Br>Cï¿½lculo de FRETE SEDEX a cobrar: ". 
calculaFrete('40045','26255170','96825150','0.1')."<br>";

echo "<br><Br>Cï¿½lculo de FRETE SEDEX 10: ". 
calculaFrete('40215','26255170','96825150','0.1')."<br>";
*/



/************************************* Funï¿½ï¿½es relacionadas a envio e recebimento de e-mails *************************************/


function extract_email_address ($string) {
    foreach(preg_split('/ /', $string) as $token) {
        $email = filter_var(filter_var($token, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
        if ($email !== false) {
            $emails[] = $email;
        }
    }
    return $emails;
}


function clean_message($info) {
	$search  = array ('<', '>'); 
	$replace = array ('',  '');
	return str_replace($search, $replace, $info);
}

function retrieve_message($mbox, $messageid)
{
   $message = array();
   
   $header = imap_header($mbox, $messageid);
   $structure = imap_fetchstructure($mbox, $messageid);

   $message['subject'] = $header->subject;
   $message['fromaddress'] =   $header->fromaddress;
   $message['toaddress'] =   $header->toaddress;
   $message['ccaddress'] =   $header->ccaddress;
   $message['date'] =   $header->date;

  if (check_type($structure))
  {
   $message['body'] = imap_fetchbody($mbox,$messageid,"1"); ## GET THE BODY OF MULTI-PART MESSAGE
   if(!$message['body']) {$message['body'] = '[NO TEXT ENTERED INTO THE MESSAGE]\n\n';}
  }
  else
  {
   $message['body'] = imap_body($mbox, $messageid);
   if(!$message['body']) {$message['body'] = '[NO TEXT ENTERED INTO THE MESSAGE]\n\n';}
  }
  
  return $message;
}

function check_type($structure) ## CHECK THE TYPE
{
  if($structure->type == 1)
    {
     return(true); ## YES THIS IS A MULTI-PART MESSAGE
    }
 else
    {
     return(false); ## NO THIS IS NOT A MULTI-PART MESSAGE
    }
}

// Necessita carregar antes a classe de envio de e-mail
function sendbymail() {
	global $mailing;
	global $attachment;
	$mail = new PHPMailer(true); 
	$mail->SetFrom($mailing[from][email], $mailing[from][nome]);
	$mail->AddReplyTo($mailing[reply_to][email], $mailing[reply_to][nome]);
	$mail->AddAddress($mailing[address][email], $mailing[address][nome]);
	$mail->Subject = $mailing[subject];
	$mail->AltBody = 'Para visualizar essa mensagem voce necessita de um leitor de e-mails que entenda formatos em HTML'; // optional - MsgHTML will create an alternate automatically
	$mail->MsgHTML($mailing[message]);
//	$mail->AddAttachment('images/phpmailer.gif');      // attachment
//	$mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
	if(!$mail->Send())  {
     echo $mail->ErrorInfo;
  }
}



/************************************* Funï¿½ï¿½es relacionadas a anï¿½lise de trï¿½fego *************************************/

function getbrowser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
   
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
        $bname = 'Apple Safari';
        $ub = "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Opera';
        $ub = "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent))
    {
        $bname = 'Netscape';
        $ub = "Netscape";
    }
   
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
   
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
   
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
   
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
}


/************************************* VALIDAR OBJETO *************************************/




function valida_objeto($objeto) {

	$objeto = trim(strtoupper($objeto));
	$parte1 = substr($objeto, 0, 2);	// retorna "CN"
	$parte2 = substr($objeto, 2, 9);	// retorna "123456789"
	$parte3 = substr($objeto, 11, 2);	// retorna "BR"
	
	if ( (strlen($objeto)<>13) ) {
		$erro[]='Objeto Invï¿½lido - Nï¿½o pode ser menor nem maior que 13 caracteres';
	}
	else {
			
		if( (!preg_match("/^([a-z]+)$/i",$parte1)) ) {
			$erro[]='Parte 1 - Objeto Invï¿½lido - Nï¿½o pode ser nï¿½merico';
		}
		
		if ( (!is_numeric($parte2)) ) {
			$erro[]='Parte 2 - Objeto Invï¿½lido - Nï¿½o ï¿½ nï¿½merico';
		}
		
		if ( ($parte3<>'BR') ) {
			$erro[]='Parte 3 - Objeto Invï¿½lido - Nï¿½o pode ser diferente de (BR)';
		}
	
	}
	
	if ( (isset($erro)) ) {
		return $erro;
	}
	else {
		return true;
	}

}

/************************************* Funï¿½ï¿½es nï¿½o categorizadas *************************************/

function paginador() {
	global $resultados_totais,$rpp,$arquivo,$inicio,$ver;
	$pt = explode(".",($resultados_totais/$rpp));
	if ($pt[1] > 0) { $ptot=($pt[0]+1); } else { $ptot=$pt[0]; }
	for ($x=0;$x<$ptot;$x++) {
		$paginador .= '<a href="' . "$arquivo" . '.php?ver=' . $ver . '&inicio=' . ($x*$rpp) . '"><span class="paginador_link">' . ($x+1) . '</span></a>'; 
	}
	return($paginador);
}

/**
 * Funï¿½ï¿½o para remover numeros de uma string
 * 
 */

 public function removerNumbers($string)
 {
	str_replace( range( 0, 9 ), null, $string );
	preg_replace( '/\d+$/', null, $string );

	return $string;
 }

 
    /**
     * 
     * função que padroniza json decode
     */
	function array_map_deep($array, $callback)
    {
        $new = array();
        foreach($array as $key => $val)
        {
            if (is_array($val))
            {
                $new[$key] = $this->array_map_deep($val, $callback);

            } else {
                $new[$key] = call_user_func($callback, $val);
            }
      }

        return $new;
	  }

	function array_map_deep_encodings($array, $callback)
    {
        $new = array();
        foreach($array as $key => $val)
        {
            if (is_array($val))
            {
                $new[$key] = $this->array_map_deep_encodings($val, $callback);

            } else {

                $new[$key] = $this->convert_encoding($val,$callback);
            }
      }

        return $new;
	  }

	  function detect_encoding($string)
		{
			////w3.org/International/questions/qa-forms-utf-8.html
			if (preg_match('%^(?: [\x09\x0A\x0D\x20-\x7E] | [\xC2-\xDF][\x80-\xBF] | \xE0[\xA0-\xBF][\x80-\xBF] | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} | \xED[\x80-\x9F][\x80-\xBF] | \xF0[\x90-\xBF][\x80-\xBF]{2} | [\xF1-\xF3][\x80-\xBF]{3} | \xF4[\x80-\x8F][\x80-\xBF]{2} )*$%xs', $string))
				return 'UTF-8';
		
			//If you need to distinguish between UTF-8 and ISO-8859-1 encoding, list UTF-8 first in your encoding_list.
			//if you list ISO-8859-1 first, mb_detect_encoding() will always return ISO-8859-1.
			return mb_detect_encoding($string, array('UTF-8', 'ASCII', 'ISO-8859-1', 'JIS', 'EUC-JP', 'SJIS'));
		}
 
	function convert_encoding($string, $to_encoding, $from_encoding = '')
	{
		if ($from_encoding == '')
			$from_encoding = $this->detect_encoding($string);
	
		if ($from_encoding == $to_encoding)
			return $string;
	
		return mb_convert_encoding($string, $to_encoding, $from_encoding);
	}


	  function charset_decode_utf_8 ($string)
	  {
			/* Only do the slow convert if there are 8-bit characters */
			/* avoid using 0xA0 (\240) in ereg ranges. RH73 does not like that */
			if (! preg_match("[\200-\237]", $string) and ! preg_match("[\241-\377]", $string))
				return $string;
	
			// decode three byte unicode characters
			$string = preg_replace("/([\340-\357])([\200-\277])([\200-\277])/", 
			"'&#'.((ord('\\1')-224)*4096 + (ord('\\2')-128)*64 + (ord('\\3')-128)).';'",   
			$string);
	
			// decode two byte unicode characters
			$string = preg_replace("/([\300-\337])([\200-\277])/",
			"'&#'.((ord('\\1')-192)*64+(ord('\\2')-128)).';'",
			$string);
	
			return $string;
		}


	  /**
	   * converte parametros
	   * 
	   */
	  
	  function utf8_string_array_encode(&$array)
	  {
		$func = function(&$value,&$key){
			if(is_string($value)){
				$value = utf8_encode($value);
			}
			if(is_string($key)){
				$key = utf8_encode($key);
			}
			if(is_array($value)){
				$this->utf8_string_array_encode($value);
			}
		};

		array_walk($array,$func);
		return $array;
	}

  	function contaString($string) {
		return strlen($string);
	}

	/*************************************************************************************************************
	|Função: UpperCase
	|Justificativa: A função nativa do PHP mb_strtoupper não funciona na nossa versão
	|Parâmetro necessário: String
	|Para realização do output dessa função no nosso sistema (salvar no BD) deve-se utilizar a função utf8_decode()
	**************************************************************************************************************/ 

	function UpperCase($text) {

		$accentLower = array("á","é","í","ó","ú","â","ê","ô","ã","õ","à","è","ì","ò","ù","ç");
		$accentUpper = array("Á","É","Í","Ó","Ú","Â","Ê","Ô","Ã","Õ","À","È","Ì","Ò","Ù","Ç");
		$newCase = str_replace($accentLower, $accentUpper, $text);

		return strtoupper(utf8_encode($newCase));

	}


	public function limparNumerosCinco($string)
    {
          $final = "";

          if(!empty($string))
          {

			if(strlen($string) == 12 or strlen($string) == 13)
			{

				$final = \substr($string,2,strlen($string));

			}else{

				$final = $tring;
			}
                  

          }

		  unset($string);

      return $final;

    }
  
	  


}

//fim da classe