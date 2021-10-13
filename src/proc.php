<?php
/**
 * Processamento dos resumos de 2019.
 * Exemplo de uso:
 *  $ cd SBPqO
 *  $ php src/proc.php -x etapa01a
 */

include(__DIR__."/lib.php");

/* estranho depois rever como usar
$cmd = array_pop($io_options_cmd); // ignora demais se houver mais de um
if (!$cmd)
	die( "\nERRO, SEM COMANDO\n" );
elseif (count($io_options_cmd)) {
	die("\nERRO, MAIS DE UM COMANDO, SÓ PODE UM\n");
}
if ($cmd == 'help')
	die($io_usage);
elseif ($cmd=='version')
	die(" lib.php version $LIBVERS\n");
*/

$cmd=$MODO='';
$jsonOpts = (JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

if (isset($argv[0])) {
	// TERMINAL:
	array_shift($argv);
	for($i=0; $i<$argc; $i++) if (isset($argv[$i]) && substr($argv[$i],0,1)=='-') {
		$MODO = strtolower( substr($argv[$i],1) );
		array_splice($argv, $i, 1);
		break;
	}
	if (empty($argv)) $cmd='';
	else $cmd=$argv[0];
} else echo "\nERRO: nao parece terminal";


$dir_recebido = "$io_baseDir/recebidoOriginal/Resumos";
$dir_entrega = "$io_baseDir/entregas";
$debug =1;
$allNodes = [];
$nodeNames = [];

switch ($cmd) {
    case "etapa01":
    case "etapa01a":
	echo "\n -- etapa01a - Lendo XMLs originais, conferindo e gravando como UTF8 -- ";
	foreach( glob("$dir_recebido/*.xml") as $f0 ) {
		$f=realpath($f0);
		$pinfo = pathinfo($f);
		$xml = file_get_contents($f);
		$sxml_resumos = new SimpleXMLElement($xml);
		print "\n-- {$pinfo['basename']}: "
		   .$sxml_resumos->Resumo[0]->Sigla ." .. ". $sxml_resumos->Resumo[$sxml_resumos->count() -1]->Sigla;
		$dom = dom_import_simplexml($sxml_resumos);
		$enc = $dom->ownerDocument->encoding;
		if ($enc=='iso-8859-1') {
			$fupath = realpath("{$pinfo['dirname']}/../entregas/etc");
			$fu = str_replace('.xml',".xml",$pinfo['basename']);
			$dom2 = new DOMDocument();
			$dom2->loadXML($xml);
			$dom2->encoding = 'utf-8'; // convert document encoding to UTF8
            		$xml = $dom2->saveXML(); // return valid, utf8-encoded XML
			$sxml2 = simplexml_load_string($xml,'SimpleXMLElement', LIBXML_NOCDATA);
			$f2 = "$dir_recebido/$fu";
			$xml = $sxml2->asXML(); //
			file_put_contents( $f2, $xml );
		}
		$dit = new RecursiveIteratorIterator(
			    new RecursiveDOMIterator($dom),
			    RecursiveIteratorIterator::SELF_FIRST);
		foreach($dit as $node) {
		    if($node->nodeType === XML_ELEMENT_NODE) {
					$x = preg_replace('/\[\d+\]/su','[]', $node->getNodePath() );
					if (isset($allNodes[$x]))  $allNodes[$x]++; else $allNodes[$x]=1;
					$nodeNames[$node->nodeName]=1;
		    }
		}
	} // all files
	$nodePaths = array_keys($allNodes);
	$nodeNames = array_keys($nodeNames);
	$numItems = $allNodes[$nodePaths[0]];
	$is_allSame = array_reduce(
		array_values($allNodes),
		function ($c,$i) use ($numItems) { $c = $c && ($i==$numItems); return $c; },
		1
	);
	if (!$is_allSame) print(PHP_EOL."!Erro, confira inconsitências:\n".json_encode($allNodes,$jsonOpts));
	print "\nNode paths: ".join($nodePaths,"; ");
	print "\nNode names: ".join($nodeNames,"; ");
  break; // etapa01a

    case "etapa01b":
	echo "\n -- etapa01b - Convertendo residuos XHTML do CDATA -- ";
	foreach( glob("$dir_recebido/*.xml") as $f0 ) {
		$f=realpath($f0);
		$pinfo = pathinfo($f);
		$xml = file_get_contents($f);
		$sxml_resumos = new SimpleXMLElement($xml);
		print "\n-- {$pinfo['basename']}: "
		   .$sxml_resumos->Resumo[0]->Sigla ." .. ". $sxml_resumos->Resumo[$sxml_resumos->count() -1]->Sigla;
		$dom = dom_import_simplexml($sxml_resumos);
		$enc = $dom->ownerDocument->encoding;
		if ($enc!='utf-8') die("\nERRO: UTF8 esperado nos XMLs.");

		//$xml = preg_replace('~&lt;(/)?(i|sub|sup|b|strong)&gt;~s','<$1i>',$xml);
		$n_tags = 0;
		$xml = preg_replace('~&lt;(/)?(strong|sub|sup|em|i|b)&gt;~sim','<$1$2>',$xml,-1, $n_tags);
		//$xml = preg_replace('~&lt;(/)?[a-z][a-z0-9]+&gt;~si','<$1code>',$xml, -1, $n_fails); // ignoring other tags, converting e.g. H1 to italics
		$xml = preg_replace('/&amp;#(\d+);/s','&#$1;',$xml); // case sensitive
		$e01b_file = "$dir_recebido/{$pinfo['basename']}";
		file_put_contents($e01b_file, $xml);
		if ($n_tags) print "\n !tags on $e01b_file! $n_tags, check if more.";
		//$sxml_resumos = new SimpleXMLElement($xml); die(PHP_EOL.$sxml_resumos->asXML());
		// já poderia fazer mb_chr ( int $cp [, string $encoding ] ) e conferir tabela de símbolos.
		// mb_convert_encoding($profile, 'HTML-ENTITIES', 'UTF-8'));
	} // all files

        break; // etapa01b

    case "etapa01c":
	// falta testar depois do git add html_entity_decode($xml,ENT_HTML5,'UTF-8')  ou mb_convert_encoding($profile, 'HTML-ENTITIES', 'UTF-8'))
	$CH_report=[];
	$DIAC_TO = [
		// diacrílicos para acentos do portugues vigente:
		'c&#807;'=>"ç",    'C&#807;'=>"Ç",
		'a&#771;'=>"ã", 'o&#771;'=>"õ",  'A&#771;'=>"Ã", 'O&#771;'=>"Õ",
		'a&#769;'=>"á", 'e&#769;'=>"é", 'i&#769;'=>"í", 'o&#769;'=>"ó", 'u&#769;'=>"ú",
		'A&#769;'=>"Á", 'E&#769;'=>"É", 'I&#769;'=>"Í", 'O&#769;'=>"Ó", 'U&#769;'=>"Ú",
		'a&#770;'=>"â", 'e&#770;'=>"ê", 'o&#770;'=>"ô",
		'a&#768;'=>"à",
		// diacrílicos para nomes estrangeiros:
		'o&#776;'=>"ö", 'u&#776;'=>"ü", // ex. Grödig and Müller
		// ("&#8315;²" == "⁻²") dual para conveter caracter invalido 8315 em maca SUP:
		'&#8315;¹'=>"<sup>-1</sup>", '&#8315;²'=>"<sup>-2</sup>", '&#8315;³'=>"<sup>-3</sup>", // sup ISO
		'&#8315;⁴'=>"<sup>-8</sup>", '&#8315;⁸'=>"<sup>-4</sup>",
		// ("&#713;¹" == 'ˉ¹') dual para conveter caracter invalido 713 em maca SUP: "&#8315;²" == "⁻²"
		'&#713;¹'=>"<sup>-1</sup>", '&#713;²'=>"<sup>-2</sup>", '&#713;³'=>"<sup>-3</sup>", // sup ISO
		'&#713;⁴'=>"<sup>-8</sup>", '&#713;⁸'=>"<sup>-4</sup>",

	];
	$DIAC_REGEX = "/". join('|', array_keys($DIAC_TO) ). "/s";
	$CH_TO = [  // falta decidir se &#64257; é "fi" ou bug.
		'ƞ'=>'η', 'ɑ'=>'α', '∆' =>'Δ', '⍺'  =>'α', '𝜎'=>'σ', '△'=>'Δ', //  greek normalization
		'∕'=>'÷', 'ː'=>':', 'ĸ'=>'κ', '‐'=>'-', 'ō'=>'õ', 'ā'=>'ã', 'ƛ'=>'λ',';'=>';', // etc. normalization
		'˂'=>'&lt;', '˃'=>'&gt;', // expand to entity
		'¹'=>"<sup>1</sup>", '²'=>"<sup>2</sup>", '³'=>"<sup>3</sup>", // ISO expand to tag
		'𝑝'=>"<i>p</i>", '⁴'=>"<sup>8</sup>", '⁸'=>"<sup>4</sup>",     // non-ISO to tag
	];
	$CH_OK = [351,730,8733,8773,8776,8800,8804,8722,8805, // bons
		8729,1178,1008, //  revisar esses
	];

	$CH_toSp = [8232]; // check it before

	$CH_NBSP = [8195,8201,8202,59154,61617]; // check it before
	$NBSP = '&#165;'; // config to real or " " commom space.
	$CH_DEL = [8203,8206]; // danger, check it before
	$n=0;
	echo "\n -- $cmd - Convertendo (e contando) entidades numéricas dos XMLs originais -- ";
	foreach( glob("$dir_recebido/*.xml") as $f0 ) {
		$CH_reportBug=[];
		$n=0;
		$f=realpath($f0);
		$pinfo = pathinfo($f);
		$f2 = $pinfo['basename'];
		echo "\n\t$f2: ";
		$xml = file_get_contents($f);
		$xml = preg_replace_callback(
			$DIAC_REGEX
			,function ($m) use(&$CH_report, &$DIAC_TO, &$n) {
				$from = $m[0]; $to = $DIAC_TO[$from]; $n++;
				if (!isset($CH_report[$from])) $CH_report[$from]=0;
				$CH_report[$from]++;
				return $to;
			}
			,$xml
		);
		$xml = preg_replace_callback(
			'/&#(\d+);/s'
			,function ($m) use(&$CH_report, &$CH_TO, &$CH_OK, &$CH_reportBug, &$CH_DEL, &$CH_NBSP, $NBSP, &$CH_toSp, &$n) {
				$chOrd = $m[1]; $ch=mb_chr($chOrd); $n++;
				if (!isset($CH_report[$chOrd])) $CH_report[$chOrd]=0;
				$CH_report[$chOrd]++;
				if (isset($CH_TO[$ch]))
					return $CH_TO[$ch];
				$chOrd = (int) $chOrd;
				if (($chOrd>900 && $chOrd<1000) || in_array($chOrd,$CH_OK) )
					return $ch;
				if (in_array($chOrd,$CH_DEL))
					return '';
				if (in_array($chOrd,$CH_toSp))
					return ' ';
				if (in_array($chOrd,$CH_NBSP))
					return $NBSP;
				$CH_reportBug[$m[0]]=1; // reporting
				return $m[0];
			}
			,$xml
		);
		$bugs = array_keys($CH_reportBug);
		if (count($bugs)) print "\n\t\tVERIFICAR: ".join(" ",$bugs);
		print "\n\t\tTOTAL $n entities.";
		file_put_contents( $f, $xml );  // gava por cima

	} // all files
	$all = array_keys($CH_report);
	sort($all,SORT_NUMERIC);
	foreach($all as $i) if ((int) $i > 0) print "\n chr($i)=".mb_chr($i,'UTF-8')." *$CH_report[$i]";
        break; // etapa01c


    case "etapa01d":
	echo "\n -- $cmd - xxx -- ";

		//$sxml_resumos = new SimpleXMLElement($xml); die(PHP_EOL.$sxml_resumos->asXML());
		// já poderia fazer mb_chr ( int $cp [, string $encoding ] ) e conferir tabela de símbolos.
		// mb_convert_encoding($profile, 'HTML-ENTITIES', 'UTF-8'));

	break; // etapa01c

    case "etapa02":
	echo "\n -- $cmd - Gerando HTML -- ";
		//$sxml_resumos = new SimpleXMLElement($xml); die(PHP_EOL.$sxml_resumos->asXML());
		// já poderia fazer mb_chr ( int $cp [, string $encoding ] ) e conferir tabela de símbolos.
		// mb_convert_encoding($profile, 'HTML-ENTITIES', 'UTF-8'));

	break; // etapa01c


    case "etapa03":
// PERIGO, separar apenas CSV, o SimpleXML tá comendo, testar com DOM e com NodeDOM... Ou simplesmente regex para compor XML.
// as estatitsticas e alterações em massa se aplicam apenas aos conteudos body. Nada de front.

	echo "\n -- $cmd - Consolidando e convertendo XMLs em planilhas -- ";
	// juntar tudo num só e depois dividir estruturalmente.
	$XML_body = '';
	$IDs=[];
	$n=0;
	$ff2_path = "$dir_entrega/etapa02";
	if ( !file_exists($ff2_path) ) mkdir($ff2_path);
	$ff2 = "$ff2_path/fronts.csv";
	$fp2 = fopen($ff2, 'w'); //  a+
	fputcsv($fp2, ['ID', 'Email', 'Titulo', 'Universidade', 'Autores', 'Apoio', 'Conflito']);
	// echo "\n	DEBUG $ff2\n"; exit(0);
	foreach( glob("$dir_recebido/*.xml") as $f0 ) {
		$f=realpath($f0);
		$pinfo = pathinfo($f);
		$xml = file_get_contents($f);
		$sxml_resumos = new SimpleXMLElement($xml);
		foreach($sxml_resumos->Resumo as $r) {
			$n++;
			$id = strtoupper( trim($r->Sigla) );
			// BUG pois não publica os EMs. 1 XML 2 XHTML.
			$XML_body .= "\n\n<article><h1>$id</h1>"
					."\n<section class='main'>\n". trimTags($r->Resumo->asXML(),'Resumo') ."\n</section>"
					."\n<section class='conclusao'>\n". trimTags($r->Conclusao->asXML(),'Conclusao') ."\n</section>"
					//."\n<section class='conflito'>\n". trim($r->Conflito) ."\n</section>"
					."\n</article>\n";
			$CSV_front = [
				'ID'=> $id,
				'Email'=> $r->Email,
				'Titulo'=> trimTags($r->Titulo->asXML(),'Titulo'),
				'Universidade'=> $r->Universidade,
				'Autores'=> $r->Autores,
				'Apoio'=> (trim($r->Apoio))? $r->Apoio: $r->Apoio->em,
				'Conflito'=> $r->Conflito,
			];
			// if ( trim($r->Apoio) || trim($r->Apoio->em) ) echo "\n $id COM APOIO.";
			fputcsv($fp2, $CSV_front);
			$IDs[$id]=1;
		}
		$n_ids = count(array_keys($IDs));
		print "\n-- {$pinfo['basename']}: $n=$n_ids items montados";
		if ($n!=$n_ids) print "\n ERRO: contagens não batem.\n";
	} // all files
	fclose($fp2);
	file_put_contents( "$dir_entrega/etapa02/bodies.html",    htmlTpl($XML_body, "RESUMOS") );
        break; // etapa02


    default:
	print_r($io_options_cmd);
        echo "$cmd INVALIDO";
}

print "\n";

// data vieu, https://observablehq.com/@d3/brushable-scatterplot-matrix
//http://bl.ocks.org/alansmithy/e984477a741bc56db5a5
//https://bost.ocks.org/mike/constancy/
// voto racial https://www.nytimes.com/interactive/2016/06/10/upshot/voting-habits-turnout-partisanship.html?rref=collection%2Fbyline%2Famanda-cox&action=click&contentCollection=undefined&region=stream&module=stream_unit&version=latest&contentPlacement=4&pgtype=collection
