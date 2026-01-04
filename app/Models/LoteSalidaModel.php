<?php

namespace App\Models;

use CodeIgniter\Model;

class LoteSalidaModel extends Model
{
    protected $table            = 'lote_salida';
    protected $primaryKey       = 'id_salida';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $deletedField   = 'deleted_at';

    protected $allowedFields = [
        'folio_salida',
        'fecha_embarque',
        'nombre_cliente',
        'producto',
        'tipo_producto',
        'unidad',
        'cantidad',
        'no_maquila',
        'no_factura',
        'certificado',
        'clave_lote',
        'datos_transporte',
        'recibe_producto',
        'autoriza_salida',
    ];

    // Reglas de validación dinámicas para manejar el is_unique en updates
    protected $validationRules = [
        'folio_salida'     => 'required|max_length[10]|is_unique[lote_salida.folio_salida,id_salida,{id_salida}]',
        'fecha_embarque'   => 'required|valid_date',
        'nombre_cliente'   => 'required|max_length[100]',
        'producto'         => 'required|max_length[150]',
        'tipo_producto'    => 'required|in_list[Orgánico,Convencional]',
        'unidad'           => 'required|max_length[20]',
        'cantidad'         => 'required|numeric|greater_than[0]',
    ];

    // ... (Mensajes de validación se mantienen igual)

    protected $beforeInsert   = ['sanitizeData', 'setDefaultValues'];
    protected $beforeUpdate   = ['sanitizeData'];
    protected $afterInsert    = ['clearCache'];
    protected $afterUpdate    = ['clearCache'];
    protected $afterDelete    = ['clearCache'];

    /**
     * Sanitiza los datos: Quitamos htmlspecialchars para evitar doble escape
     * ya que la vista rediseñada ya usa esc().
     */
    protected function sanitizeData(array $data): array
    {
        if (isset($data['data'])) {
            $data['data'] = array_map(function($value) {
                if (is_string($value)) {
                    // Solo quitamos espacios extra, no convertimos a HTML entities aquí
                    return trim($value);
                }
                return $value;
            }, $data['data']);
        }
        return $data;
    }

    /**
     * Mejora del generador de Folio
     */
    public function getNextFolio(): string
    {
        // Buscamos el último registro incluso si fue borrado lógicamente para no repetir folios
        $lastRow = $this->withDeleted()
            ->select('folio_salida')
            ->orderBy('id_salida', 'DESC')
            ->first();

        if (!$lastRow) {
            return '0001';
        }

        // Extraer solo los números por si el folio tiene prefijos como "NS-"
        preg_match_all('!\d+!', $lastRow['folio_salida'], $matches);
        $lastNumber = isset($matches[0][0]) ? (int)$matches[0][0] : 0;

        return str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Limpia el caché de años y estadísticas además de las salidas del mes
     */
    protected function clearCache(array $data): array
    {
        $cache = \Config\Services::cache();
        $cache->delete('salidas_mes');
        $cache->delete('available_years_lote_salida'); // Cache usado en el controlador
        return $data;
    }
}