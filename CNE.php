<?php
namespace CNE;

/**
 * @author Félix Galindo - github <@fxgal> - email <fxgal.dev@gmail.com>
 * Módulo de búsqueda de datos personales en la BD del Consejo Nacional Electorar de Venezuela
 */
class CNE
{
    private $nacionalidad;
    private $cedula;
    private $url;
    private $content;
    /**
     * Constructor
     * @param  integer $cedula       Número de cédula del ciudadano
     * @param  string $nacionalidad V=Venezolano E=Extranjero
     */
    public function __construct($cedula = null, $nacionalidad = 'V')
    {
        $this->cedula = $cedula;
        $this->nacionalidad = $nacionalidad;
        $this->content = [];
        $this->url = 'http://www.cne.gob.ve/web/registro_electoral/ce.php?';
    }

    public function __set($var, $valor)
    {
        if (property_exists(__CLASS__, $var)) {
            $this->$var = $valor;
        } else {
            echo "No existe el atributo $var.";
        }
    }
    public function __get($var)
    {
        if (property_exists(__CLASS__, $var)) {
            return $this->$var;
        }
        return null;
    }
    /**
     * Realiza una busqueda según los datos iniciales del constructor o nuevos parámetros
     * @param  integer $cedula       Número de cédula del ciudadano
     * @param  string $nacionalidad V=Venezolano E=Extranjero
     * @return json               Resultado de búsqueda
     */
    public function search($cedula = null, $nacionalidad = 'V')
    {
        $cedula = isset($cedula)?$cedula:$this->cedula;
        $nacionalidad = isset($nacionalidad)?$nacionalidad:$this->nacionalidad;
        if (!isset($cedula)) {
            return json_encode([
            'status'=>'error',
            'message'=>'Datos incorrectos'
          ]);
        }
        $url = $this->url."nacionalidad=$nacionalidad&cedula=$cedula";
        $client = curl_init();
        curl_setopt($client, CURLOPT_URL, $url);
        curl_setopt($client, CURLOPT_HEADER, false);
        curl_setopt($client, CURLOPT_NOBODY, false); // remove body
        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
        if (curl_errno($client)) {
            echo 'Scraper error: ' . curl_error($client);
            exit;
        }
        $page = curl_exec($client);
        $page = str_replace('<b>', '', $page);
        $page = str_replace('</b>', '', $page);
        $page = str_replace('<font color="#00387b">', '', $page);
        $page = str_replace('<font color="#0000FF">', '', $page);
        $page = str_replace('</font>', '', $page);
        $page = str_replace('<center>', '', $page);
        $page = str_replace('</center>', '', $page);
        preg_match_all("/<td align=\"left\">(.*?)<\/td\>/", $page, $matches);
        if (count($matches[1])) {
            $data = $matches[1];
            $this->content = [
              'CEDULA'=>$data[1],
              'NOMBRE'=> $data[3],
              'ESTADO'=>$data[5],
              'MUNICIPIO'=>$data[6],
              'PARROQUIA'=>$data[9],
              'CENTRO'=>$data[10],
              'DIRECCION'=>$data[12],
            ];
        } else {
            return json_encode([
              'status'=>'warning',
              'message'=>'Datos no encontrados'
            ]);
        }
        curl_close($client);
        return json_encode($this->content);
    }
}
