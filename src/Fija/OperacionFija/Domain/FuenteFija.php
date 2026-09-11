<?php

namespace AMovil\Fija\OperacionFija\Domain;

use DateTime;
use Illuminate\Support\Facades\Hash;

class FuenteFija
{
    private ?int $id;
    private ?string $plano;
    private ?string $tipoNodo;
    private ?string $tipoFuente;
    private ?string $ubicadoEn;
    private ?string $tieneBaterias;
    private ?int $anioFabricacion;
    private ?string $tipoRespaldo;
    private ?string $amp;
    private ?string $candado;
    private ?string $barra;
    private ?string $seguroH;
    private ?string $seguroBaterias;
    private ?DateTime $fechaManto;
    private ?int $anioManto;
    private ?string $mesManto;
    private ?string $referido;
    private ?string $region;
    private ?string $distrito;
    private ?string $segmentoUrbano;
    private ?string $gestionCampo;
    private ?string $nivelSeguridad;
    private ?string $comentario;
    private ?float $latitud;
    private ?float $longitud;
    private array $images = [];

    public function __construct(
        ?int $id,
        ?string $plano,
        ?string $tipoNodo,
        ?string $tipoFuente,
        ?string $ubicadoEn,
        ?string $tieneBaterias,
        ?int $anioFabricacion,
        ?string $tipoRespaldo,
        ?string $amp,
        ?string $candado,
        ?string $barra,
        ?string $seguroH,
        ?string $seguroBaterias,
        ?DateTime $fechaManto,
        ?int $anioManto,
        ?string $mesManto,
        ?string $referido,
        ?string $region,
        ?string $distrito,
        ?string $segmentoUrbano,
        ?string $gestionCampo,
        ?string $nivelSeguridad,
        ?string $comentario,
        ?float $latitud,
        ?float $longitud,
    )
    {
        $this->id = $id;
        $this->plano = $plano;
        $this->tipoNodo = $tipoNodo;
        $this->tipoFuente = $tipoFuente;
        $this->ubicadoEn = $ubicadoEn;
        $this->tieneBaterias = $tieneBaterias;
        $this->anioFabricacion = $anioFabricacion;
        $this->tipoRespaldo = $tipoRespaldo;
        $this->amp = $amp;
        $this->candado = $candado;
        $this->barra = $barra;
        $this->seguroH = $seguroH;
        $this->seguroBaterias = $seguroBaterias;
        $this->fechaManto = $fechaManto;
        $this->anioManto = $anioManto;
        $this->mesManto = $mesManto;
        $this->referido = $referido;
        $this->region = $region;
        $this->distrito = $distrito;
        $this->segmentoUrbano = $segmentoUrbano;
        $this->gestionCampo = $gestionCampo;
        $this->nivelSeguridad = $nivelSeguridad;
        $this->comentario = $comentario;
        $this->latitud = $latitud;
        $this->longitud = $longitud;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getPlano(): ?string {
        return $this->plano;
    }

    public function getTipoNodo(): ?string {
        return $this->tipoNodo;
    }

    public function getTipoFuente(): ?string {
        return $this->tipoFuente;
    }

    public function getUbicadoEn(): ?string {
        return $this->ubicadoEn;
    }

    public function getTieneBaterias(): ?string {
        return $this->tieneBaterias;
    }

    public function getAnioFabricacion(): ?int {
        return $this->anioFabricacion;
    }

    public function getTipoRespaldo(): ?string {
        return $this->tipoRespaldo;
    }

    public function getAmp(): ?string {
        return $this->amp;
    }

    public function getCandado(): ?string {
        return $this->candado;
    }

    public function getBarra(): ?string {
        return $this->barra;
    }

    public function getSeguroH(): ?string {
        return $this->seguroH;
    }

    public function getSeguroBaterias(): ?string {
        return $this->seguroBaterias;
    }

    public function getFechaManto(): ?DateTime {
        return $this->fechaManto;
    }

    public function getAnioManto(): ?int {
        return $this->anioManto;
    }

    public function getMesManto(): ?string {
        return $this->mesManto;
    }

    public function getReferido(): ?string {
        return $this->referido;
    }

    public function getRegion(): ?string {
        return $this->region;
    }

    public function getDistrito(): ?string {
        return $this->distrito;
    }

    public function getSegmentoUrbano(): ?string {
        return $this->segmentoUrbano;
    }

    public function getGestionCampo(): ?string {
        return $this->gestionCampo;
    }

    public function getNivelSeguridad(): ?string {
        return $this->nivelSeguridad;
    }

    public function getComentario(): ?string {
        return $this->comentario;
    }

    public function getLatitud(): ?float {
        return $this->latitud;
    }

    public function getLongitud(): ?float {
        return $this->longitud;
    }

    public function getImages(): array {
        return $this->images;
    }

    public function getPublicImages(): array {
        $mappedImages = [];
        foreach($this->images as $path){
            $mappedImages[] = asset($path);
        }
        return $mappedImages;
    }

    public function addImage(string $path){
        $this->images[] = $path;
    }

    public function setImages(array $images){
        $this->images = $images;
    }

    public function toArray(){
        return [
            "id" => $this->getId(),
            "plano" => $this->getPlano(),
            "tipo_nodo" => $this->getTipoNodo(),
            "tipo_fuente" => $this->getTipoFuente(),
            "ubicado_en" => $this->getUbicadoEn(),
            "tiene_baterias" => $this->getTieneBaterias(),
            "anio_fab" => $this->getAnioFabricacion(),
            "tipo_respaldo" => $this->getTipoRespaldo(),
            "amp" => $this->getAmp(),
            "candado" => $this->getCandado(),
            "barra" => $this->getBarra(),
            "seguro_h" => $this->getSeguroH(),
            "seguro_baterias" => $this->getSeguroBaterias(),
            "fecha_manto" => $this->getFechaManto() !== null ? $this->getFechaManto()->format("Y-m-d") : null,
            "anio_manto" => $this->getAnioManto(),
            "mes_manto" => $this->getMesManto(),
            "referido" => $this->getReferido(),
            "region" => $this->getRegion(),
            "distrito" => $this->getDistrito(),
            "segmento_urbano" => $this->getSegmentoUrbano(),
            "gestion_campo" => $this->getGestionCampo(),
            "nivel_de_seguridad" => $this->getNivelSeguridad(),
            "comentario" => $this->getComentario(),
            "latitud" => $this->getLatitud(),
            "longitud" => $this->getLongitud(),
            "images" => $this->getPublicImages(),
        ];
    }
}
