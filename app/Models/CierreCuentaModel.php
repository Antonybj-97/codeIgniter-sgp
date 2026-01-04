<?php 
namespace App\Models;

use CodeIgniter\Model;

class CierreCuentaModel extends Model
{
    protected $table            = 'cierres_cuenta';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'centro', 'fecha', 'acopiador', 'cosecha', 'folio',
        'dinero_entregado', 'otros_cargos', 'dinero_comprobado', 'saldo_acopiador',
        'importe_total_pimienta', 'total_comisiones', 'total_a_pagar', 'saldo_final',
        'rendimiento_beneficio', 'rendimiento_centro', 'rendimiento_general',
        'obs_acopio_verde', 'obs_acopio_seca',
        'firmo_elaboro', 'firmo_autorizo', 'firmo_acopiador',
        'pimienta', 'almacen', 'pagos', 'comisiones'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'centro'     => 'required|max_length[150]',
        'fecha'      => 'required|valid_date',
        'acopiador'  => 'required|max_length[150]',
        'cosecha'    => 'required|max_length[10]',
        'folio'      => 'required|max_length[100]|is_unique[cierres_cuenta.folio]',
        'firmo_elaboro'    => 'required|max_length[150]',
        'firmo_autorizo'   => 'required|max_length[150]',
        'firmo_acopiador'  => 'required|max_length[150]',
    ];
    
    protected $validationMessages   = [
        'folio' => [
            'is_unique' => 'El folio ya existe en el sistema.'
        ]
    ];
    
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generarFolioSiVacio'];
    protected $beforeUpdate   = [];

    protected function generarFolioSiVacio(array $data)
    {
        // Si el folio está vacío, generar uno automáticamente
        if (empty($data['data']['folio'])) {
            $centro = substr($data['data']['centro'] ?? 'GEN', 0, 3);
            $fecha = date('Ymd', strtotime($data['data']['fecha'] ?? 'now'));
            $acopiador = substr($data['data']['acopiador'] ?? 'ACP', 0, 3);
            $random = rand(100, 999);
            
            $data['data']['folio'] = "CIE-{$centro}-{$fecha}-{$acopiador}-{$random}";
        }
        
        return $data;
    }
}