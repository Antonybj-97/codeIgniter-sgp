<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
    :root {
        --primary-green: #2E7D32;
        --primary-green-light: #4CAF50;
        --primary-green-dark: #1B5E20;
        --secondary-orange: #FF9800;
        --secondary-orange-light: #FFB74D;
        --secondary-orange-dark: #F57C00;
        --neutral-light: #F5F7FA;
        --neutral-medium: #E8EAF6;
        --neutral-dark: #37474F;
        --neutral-gray: #78909C;
        --success-light: #C8E6C9;
        --warning-light: #FFECB3;
        --danger-light: #FFCDD2;
        --info-light: #B3E5FC;
        
        --border-radius-lg: 16px;
        --border-radius-md: 12px;
        --border-radius-sm: 8px;
        --box-shadow: 0 6px 12px rgba(46, 125, 50, 0.08);
        --box-shadow-hover: 0 10px 20px rgba(46, 125, 50, 0.12);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* --- FONDO GRADIENTE --- */
    body {
        background: linear-gradient(135deg, #F8FFEE 0%, #FFF8F0 100%);
        min-height: 100vh;
    }

    /* --- ESTILOS GENERALES --- */
    .page-container {
        padding: 25px;
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from { 
            opacity: 0; 
            transform: translateY(20px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 35px;
        padding-bottom: 20px;
        border-bottom: 3px solid var(--primary-green-light);
        background: white;
        border-radius: var(--border-radius-lg);
        padding: 25px;
        box-shadow: var(--box-shadow);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-green), var(--secondary-orange));
    }

    .page-title-section {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .page-icon {
        background: linear-gradient(135deg, var(--primary-green), var(--primary-green-light));
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 12px rgba(46, 125, 50, 0.2);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .page-icon::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, transparent, rgba(255, 255, 255, 0.2));
        top: 0;
        left: 0;
    }

    .page-icon:hover {
        transform: rotate(5deg) scale(1.05);
    }

    .page-icon i {
        font-size: 2rem;
        color: white;
        z-index: 1;
    }

    .page-title h1 {
        font-weight: 800;
        color: var(--primary-green-dark);
        margin: 0;
        font-size: 2.2rem;
        line-height: 1.2;
        background: linear-gradient(135deg, var(--primary-green-dark), var(--secondary-orange-dark));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .page-title p {
        color: var(--neutral-gray);
        margin: 8px 0 0;
        font-size: 1rem;
        font-weight: 500;
        max-width: 500px;
    }

    .stats-card {
        background: linear-gradient(135deg, white, #F9FFE8);
        border-radius: var(--border-radius-md);
        padding: 20px 25px;
        box-shadow: var(--box-shadow);
        border-left: 6px solid var(--secondary-orange);
        min-width: 200px;
        text-align: center;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--box-shadow-hover);
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--secondary-orange), var(--primary-green));
    }

    .stats-card .stat-value {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--primary-green);
        line-height: 1;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        text-shadow: 0 2px 4px rgba(46, 125, 50, 0.1);
    }

    .stats-card .stat-label {
        font-size: 0.9rem;
        color: var(--neutral-gray);
        margin-top: 8px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* --- TOOLBAR MEJORADA --- */
    .toolbar-container {
        background: linear-gradient(135deg, white, #FAFFE6);
        border-radius: var(--border-radius-lg);
        padding: 25px;
        box-shadow: var(--box-shadow);
        margin-bottom: 30px;
        border: 2px solid #E8F5E9;
        position: relative;
        overflow: hidden;
    }

    .toolbar-container::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(76, 175, 80, 0.05) 0%, transparent 70%);
    }

    .toolbar-section {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        align-items: center;
    }

    .toolbar-group {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 20px;
        border-right: 2px solid #E8F5E9;
        position: relative;
    }

    .toolbar-group:last-child {
        border-right: none;
    }

    .toolbar-group::after {
        content: '';
        position: absolute;
        right: -1px;
        top: 50%;
        transform: translateY(-50%);
        width: 2px;
        height: 60%;
        background: linear-gradient(to bottom, transparent, var(--primary-green-light), transparent);
    }

    .toolbar-group:last-child::after {
        display: none;
    }

    .toolbar-group.filters {
        flex-grow: 1;
        flex-wrap: wrap;
    }

    .btn-tool {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 18px;
        border-radius: var(--border-radius-sm);
        font-weight: 600;
        transition: var(--transition);
        border: 2px solid transparent;
        text-decoration: none;
        cursor: pointer;
        font-size: 0.95rem;
        white-space: nowrap;
        letter-spacing: 0.3px;
    }

    .btn-tool:hover {
        transform: translateY(-3px);
        box-shadow: var(--box-shadow-hover);
    }

    .btn-tool i {
        font-size: 1.1rem;
    }

    .btn-tool.btn-outline-secondary {
        background: white;
        border-color: #CFD8DC;
        color: #546E7A;
    }

    .btn-tool.btn-outline-secondary:hover {
        background: var(--neutral-light);
        border-color: var(--primary-green);
        color: var(--primary-green-dark);
    }

    .btn-tool.btn-success {
        background: linear-gradient(135deg, var(--primary-green), var(--primary-green-light));
        color: white;
        border: none;
    }

    .btn-tool.btn-success:hover {
        background: linear-gradient(135deg, var(--primary-green-dark), var(--primary-green));
    }

    .btn-tool.btn-outline-dark {
        background: white;
        border-color: var(--neutral-dark);
        color: var(--neutral-dark);
    }

    .btn-tool.btn-outline-dark:hover {
        background: var(--neutral-dark);
        color: white;
    }

    .btn-tool.btn-info {
        background: linear-gradient(135deg, #29B6F6, #4FC3F7);
        color: white;
        border: none;
    }

    .btn-tool.btn-outline-secondary.btn-reset {
        border-color: #FFB74D;
        color: var(--secondary-orange-dark);
    }

    .btn-tool.btn-outline-secondary.btn-reset:hover {
        background: var(--secondary-orange-light);
        border-color: var(--secondary-orange);
        color: white;
    }

    .btn-tool.btn-warning {
        background: linear-gradient(135deg, var(--secondary-orange), var(--secondary-orange-light));
        color: white;
        border: none;
    }

    .btn-tool.btn-warning:hover {
        background: linear-gradient(135deg, var(--secondary-orange-dark), var(--secondary-orange));
    }

    .btn-tool.btn-danger {
        background: linear-gradient(135deg, #F44336, #EF5350);
        color: white;
        border: none;
    }

    .btn-tool.btn-danger:hover {
        background: linear-gradient(135deg, #D32F2F, #E53935);
    }

    .btn-tool.btn-primary {
        background: linear-gradient(135deg, #2196F3, #42A5F5);
        color: white;
        border: none;
    }

    .filter-input {
        min-width: 150px;
        border-radius: var(--border-radius-sm);
        padding: 10px 14px;
        border: 2px solid #E0E0E0;
        transition: var(--transition);
        font-size: 0.95rem;
        height: 42px;
        background: white;
        color: var(--neutral-dark);
    }

    .filter-input:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.15);
        outline: none;
    }

    .filter-label {
        font-size: 0.9rem;
        color: var(--primary-green-dark);
        white-space: nowrap;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .filter-label::before {
        content: '';
        display: inline-block;
        width: 6px;
        height: 6px;
        background: var(--primary-green);
        border-radius: 50%;
    }

    /* --- FILTROS ACTIVOS --- */
    .active-filters-container {
        background: linear-gradient(135deg, #E8F5E9, #FFF3E0);
        border-radius: var(--border-radius-md);
        padding: 20px;
        margin-bottom: 25px;
        border: 2px solid var(--primary-green-light);
        animation: slideDown 0.4s ease-out;
        display: none;
        box-shadow: 0 4px 8px rgba(46, 125, 50, 0.1);
    }

    @keyframes slideDown {
        from { 
            opacity: 0; 
            transform: translateY(-15px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }

    .active-filters-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid rgba(76, 175, 80, 0.2);
    }

    .active-filters-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        color: var(--primary-green-dark);
        font-size: 1rem;
    }

    .active-filters-title i {
        color: var(--secondary-orange);
    }

    .filters-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }

    .filter-badge {
        background: linear-gradient(135deg, var(--primary-green), var(--primary-green-light));
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
        line-height: 1.2;
        font-weight: 600;
        box-shadow: 0 3px 6px rgba(46, 125, 50, 0.2);
        transition: var(--transition);
    }

    .filter-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(46, 125, 50, 0.3);
    }

    .filter-badge .remove-filter {
        cursor: pointer;
        font-size: 0.8rem;
        opacity: 0.9;
        transition: var(--transition);
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        padding: 3px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        width: 20px;
        height: 20px;
    }

    .filter-badge .remove-filter:hover {
        opacity: 1;
        transform: rotate(90deg);
        background: rgba(255, 255, 255, 0.3);
    }

    /* --- TABLA --- */
    .table-container {
        background: white;
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        box-shadow: var(--box-shadow);
        animation: fadeInUp 0.8s ease-out;
        border: 2px solid #E8F5E9;
    }

    .table-header {
        background: linear-gradient(135deg, var(--primary-green-dark), var(--primary-green));
        color: white;
        padding: 22px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        position: relative;
        overflow: hidden;
    }

    .table-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    }

    .table-header h3 {
        margin: 0;
        font-size: 1.4rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        z-index: 1;
    }

    .table-header h3 i {
        color: var(--secondary-orange-light);
    }

    .table-actions {
        display: flex;
        gap: 15px;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .input-group {
        background: rgba(255, 255, 255, 0.15);
        border-radius: var(--border-radius-sm);
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
    }

    .input-group-text {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
    }

    .input-group input {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: white;
    }

    .input-group input::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }

    .table-responsive {
        overflow-x: auto;
    }

    .custom-table {
        width: 100%;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.95rem;
    }

    .custom-table thead {
        background: linear-gradient(to bottom, #F1F8E9, #E8F5E9);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .custom-table th {
        font-weight: 700;
        color: var(--primary-green-dark);
        padding: 18px 15px;
        border-bottom: 3px solid var(--primary-green);
        white-space: nowrap;
        text-align: left;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .custom-table td {
        padding: 16px 15px;
        vertical-align: middle;
        border-bottom: 2px solid #F5F5F5;
        transition: var(--transition);
        color: var(--neutral-dark);
    }

    .custom-table tbody tr {
        transition: var(--transition);
        background: white;
    }

    .custom-table tbody tr:hover {
        background: linear-gradient(135deg, #F1F8E9, #FFF8E1);
        transform: translateX(5px);
        box-shadow: 0 4px 8px rgba(46, 125, 50, 0.1);
    }

    .custom-table tbody tr:nth-child(even) {
        background: #FAFAFA;
    }

    /* --- BADGES --- */
    .badge-custom {
        padding: 8px 14px;
        border-radius: var(--border-radius-sm);
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        display: inline-block;
        white-space: nowrap;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .badge-compra {
        background: linear-gradient(135deg, #C8E6C9, #A5D6A7);
        color: #1B5E20;
        border-left: 4px solid #2E7D32;
    }

    .badge-transferencia {
        background: linear-gradient(135deg, #BBDEFB, #90CAF9);
        color: #0D47A1;
        border-left: 4px solid #1565C0;
    }

    .badge-devolucion {
        background: linear-gradient(135deg, #FFECB3, #FFE082);
        color: #FF6F00;
        border-left: 4px solid #FF9800;
    }

    .badge-ajuste {
        background: linear-gradient(135deg, #E1BEE7, #CE93D8);
        color: #4A148C;
        border-left: 4px solid #7B1FA2;
    }

    .badge-activo {
        background: linear-gradient(135deg, #C8E6C9, #A5D6A7);
        color: #1B5E20;
        border-left: 4px solid #2E7D32;
    }

    .badge-pendiente {
        background: linear-gradient(135deg, #FFF9C4, #FFF59D);
        color: #F57F17;
        border-left: 4px solid #FFB300;
    }

    .badge-cancelado {
        background: linear-gradient(135deg, #FFCDD2, #EF9A9A);
        color: #C62828;
        border-left: 4px solid #D32F2F;
    }

    .badge-procesado {
        background: linear-gradient(135deg, #B3E5FC, #81D4FA);
        color: #01579B;
        border-left: 4px solid #0288D1;
    }

    /* --- BOTONES DE ACCIÓN --- */
    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-action {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--border-radius-sm);
        border: none;
        transition: var(--transition);
        text-decoration: none;
        cursor: pointer;
        font-size: 1rem;
        box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }

    .btn-action::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, transparent, rgba(255, 255, 255, 0.3));
    }

    .btn-action:hover {
        transform: translateY(-3px) scale(1.1);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }

    .btn-edit {
        background: linear-gradient(135deg, #FFF9C4, #FFF59D);
        color: #FF8F00;
    }

    .btn-edit:hover {
        background: linear-gradient(135deg, #FFF59D, #FFF176);
    }

    .btn-delete {
        background: linear-gradient(135deg, #FFCDD2, #EF9A9A);
        color: #D32F2F;
    }

    .btn-delete:hover {
        background: linear-gradient(135deg, #EF9A9A, #E57373);
    }

    .btn-pdf {
        background: linear-gradient(135deg, #B3E5FC, #81D4FA);
        color: #0288D1;
    }

    .btn-pdf:hover {
        background: linear-gradient(135deg, #81D4FA, #4FC3F7);
    }

    .btn-view {
        background: linear-gradient(135deg, #C8E6C9, #A5D6A7);
        color: #2E7D32;
    }

    .btn-view:hover {
        background: linear-gradient(135deg, #A5D6A7, #81C784);
    }

    /* --- BÚSQUEDA POR FOLIO --- */
    .folio-search {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: nowrap;
    }

    .folio-search-input {
        width: 140px;
        border-radius: var(--border-radius-sm);
        padding: 10px 14px;
        border: 2px solid #E0E0E0;
        transition: var(--transition);
        font-size: 0.95rem;
        height: 42px;
        background: white;
        color: var(--neutral-dark);
    }

    .folio-search-input:focus {
        border-color: var(--secondary-orange);
        box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.15);
        outline: none;
    }

    /* --- LOADING --- */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        flex-direction: column;
        gap: 20px;
        backdrop-filter: blur(5px);
    }

    .spinner {
        width: 60px;
        height: 60px;
        border: 4px solid #E8F5E9;
        border-top: 4px solid var(--primary-green);
        border-radius: 50%;
        animation: spin 1s cubic-bezier(0.68, -0.55, 0.27, 1.55) infinite;
        position: relative;
    }

    .spinner::before {
        content: '';
        position: absolute;
        top: -4px;
        left: -4px;
        right: -4px;
        bottom: -4px;
        border: 4px solid transparent;
        border-top: 4px solid var(--secondary-orange);
        border-radius: 50%;
        animation: spin 1.5s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .loading-text {
        color: var(--primary-green-dark);
        font-weight: 600;
        font-size: 1.1rem;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    /* --- MENSAJES DE ESTADO --- */
    .no-data-message {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(135deg, #FAFAFA, #F5F5F5);
        border-radius: var(--border-radius-md);
        margin: 20px;
        border: 2px dashed #BDBDBD;
    }

    .no-data-message i {
        font-size: 4rem;
        margin-bottom: 20px;
        color: #E0E0E0;
        opacity: 0.6;
    }

    .no-data-message h4 {
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--neutral-gray);
        font-size: 1.4rem;
    }

    .no-data-message p {
        color: #90A4AE;
        max-width: 400px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* --- RESPONSIVE --- */
    @media (max-width: 1200px) {
        .toolbar-group {
            padding: 0 15px;
        }
        
        .filter-input {
            min-width: 130px;
        }
    }

    @media (max-width: 992px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 25px;
        }
        
        .page-title-section {
            width: 100%;
        }
        
        .stats-card {
            align-self: stretch;
            min-width: auto;
        }

        .toolbar-section {
            flex-direction: column;
            align-items: stretch;
            gap: 25px;
        }

        .toolbar-group {
            border-right: none;
            border-bottom: 2px solid #E8F5E9;
            padding-bottom: 20px;
            justify-content: flex-start;
            width: 100%;
            flex-wrap: wrap;
        }

        .toolbar-group::after {
            display: none;
        }

        .toolbar-group:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .toolbar-group.filters {
            flex-wrap: wrap;
            justify-content: flex-start;
        }
        
        .folio-search {
            justify-content: flex-start;
        }
        
        .table-header {
            flex-direction: column;
            gap: 20px;
            align-items: flex-start;
        }
        
        .table-header h3 {
            font-size: 1.2rem;
        }
        
        .input-group {
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        .page-container {
            padding: 20px 15px;
        }
        
        .page-title-section {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .page-icon {
            width: 60px;
            height: 60px;
        }
        
        .page-icon i {
            font-size: 1.8rem;
        }
        
        .page-title h1 {
            font-size: 1.8rem;
        }
        
        .page-title p {
            font-size: 0.95rem;
        }

        .action-buttons {
            justify-content: flex-start;
        }
        
        .btn-action {
            width: 36px;
            height: 36px;
            font-size: 0.9rem;
        }

        .filter-input, .folio-search-input {
            width: 120px;
            font-size: 0.9rem;
        }
        
        .folio-search {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        
        .folio-search-input {
            width: 100%;
        }
        
        .custom-table {
            font-size: 0.9rem;
        }
        
        .custom-table th,
        .custom-table td {
            padding: 14px 10px;
        }
        
        .badge-custom {
            font-size: 0.8rem;
            padding: 6px 10px;
        }
    }
    
    @media (max-width: 576px) {
        .stats-card {
            padding: 18px 20px;
        }
        
        .stats-card .stat-value {
            font-size: 1.8rem;
        }
        
        .btn-tool {
            padding: 8px 12px;
            font-size: 0.9rem;
        }
        
        .btn-tool i {
            font-size: 1rem;
        }
        
        .filter-label {
            font-size: 0.85rem;
        }
        
        .filter-badge {
            font-size: 0.85rem;
            padding: 6px 12px;
        }
        
        .toolbar-container {
            padding: 20px;
        }
        
        .table-header {
            padding: 18px 20px;
        }
    }
</style>

<div class="page-container">

    <!-- HEADER CON ESTADÍSTICAS -->
    <div class="page-header">
        <div class="page-title-section">
            <div class="page-icon">
                <i class="bi bi-inboxes-fill"></i>
            </div>
            <div class="page-title">
                <h1>Lotes de Entrada</h1>
                <p>Gestiona y visualiza todas las entradas de pimienta en el sistema</p>
            </div>
        </div>
        <div class="stats-card">
            <div class="stat-value" id="contadorRegistros">0</div>
            <div class="stat-label">Registros Totales</div>
        </div>
    </div>

    <!-- TOOLBAR MEJORADA CON FILTROS COMBINADOS -->
    <div class="toolbar-container">
        <div class="toolbar-section">
            <div class="toolbar-group">
                <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-tool btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Dashboard
                </a>
                <a href="<?= base_url('lotes-entrada/create') ?>" class="btn btn-tool btn-success">
                    <i class="bi bi-plus-circle"></i> Nuevo Lote
                </a>
                <a href="<?= base_url('cierre') ?>" class="btn btn-tool btn-outline-dark">
                    <i class="bi bi-journal-check"></i> Cierre
                </a>
            </div>

            <!-- FILTROS COMBINADOS -->
            <div class="toolbar-group filters">
                <span class="filter-label">Año:</span>
                <select id="filtroAnio" class="form-control form-control-sm filter-input">
                    <option value="">Todos los años</option>
                    <?php
                    $y = date('Y');
                    for ($i = $y; $i >= $y - 5; $i--) {
                        echo "<option value='{$i}'>{$i}</option>";
                    }
                    ?>
                </select>

                <span class="filter-label">Tipo:</span>
                <select id="filtroTipo" class="form-control form-control-sm filter-input">
                    <option value="">Todos los tipos</option>
                    <option value="Compra">Compra</option>
                    <option value="Transferencia">Transferencia</option>
                    <option value="Devolución">Devolución</option>
                    <option value="Ajuste">Ajuste</option>
                </select>

                <span class="filter-label">Estado:</span>
                <select id="filtroEstado" class="form-control form-control-sm filter-input">
                    <option value="">Todos los estados</option>
                    <option value="Activo">Activo</option>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Cancelado">Cancelado</option>
                    <option value="Procesado">Procesado</option>
                </select>

                <button id="btnAplicarFiltros" class="btn btn-tool btn-info">
                    <i class="bi bi-funnel"></i> Aplicar Filtros
                </button>

                <button id="btnResetFiltros" class="btn btn-tool btn-outline-secondary btn-reset">
                    <i class="bi bi-x-circle"></i> Limpiar
                </button>
            </div>

            <!-- EXPORTACIONES Y BÚSQUEDA POR FOLIO -->
            <div class="toolbar-group">
                <div class="folio-search">
                    <input type="text" id="buscarFolioInput" placeholder="Nº Folio" 
                           class="form-control form-control-sm folio-search-input">
                    <button id="btnBuscarFolioPDF" class="btn btn-tool btn-warning">
                        <i class="bi bi-search"></i> Buscar PDF
                    </button>
                </div>
            </div>

            <div class="toolbar-group">
                <div class="btn-group">
                    <button id="btnExportarPDF" class="btn btn-tool btn-danger">
                        <i class="bi bi-filetype-pdf"></i> Exportar PDF
                    </button>
                    <button id="btnExportarExcel" class="btn btn-tool btn-success">
                        <i class="bi bi-filetype-xlsx"></i> Exportar Excel
                    </button>
                </div>
                <button id="btnRefresh" class="btn btn-tool btn-primary">
                    <i class="bi bi-arrow-clockwise"></i> Actualizar
                </button>
            </div>
        </div>
    </div>

    <!-- FILTROS ACTIVOS -->
    <div id="activeFiltersContainer" class="active-filters-container">
        <div class="active-filters-header">
            <div class="active-filters-title">
                <i class="bi bi-funnel-fill"></i>
                <span>Filtros aplicados:</span>
            </div>
            <button id="btnClearAllFilters" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-x-lg"></i> Quitar todos
            </button>
        </div>
        <div class="filters-badges" id="filtersBadges">
            <!-- Los badges de filtros se generan aquí dinámicamente -->
        </div>
    </div>

    <!-- TABLA MEJORADA -->
    <div class="table-container">
        <div class="table-header">
            <h3><i class="bi bi-table me-2"></i>Registros de Lotes de Entrada</h3>
            <div class="table-actions">
                <div class="input-group input-group-sm" style="min-width: 220px; max-width: 320px;">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="tableSearch" class="form-control" placeholder="Buscar en tabla...">
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table id="tablaLotes" class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Folio</th>
                        <th>Fecha</th>
                        <th>Centro</th>
                        <th>Tipo Pimienta</th>
                        <th>Tipo Entrada</th>
                        <th>Productor</th>
                        <th>Peso (kg)</th>
                        <th>Precio</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Los datos se cargan dinámicamente -->
                </tbody>
            </table>
        </div>
        
        <!-- Mensaje cuando no hay datos -->
        <div id="noDataMessage" class="no-data-message" style="display: none;">
            <i class="bi bi-inbox"></i>
            <h4>No se encontraron registros</h4>
            <p>No hay lotes de entrada con los filtros seleccionados. Intenta ajustar los filtros o crear un nuevo lote.</p>
        </div>
    </div>

</div>

<!-- LOADING OVERLAY -->
<div id="loadingOverlay" class="loading-overlay" style="display: none;">
    <div class="spinner"></div>
    <div class="loading-text">Cargando datos...</div>
</div>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/locale/es.js"></script>

<script>
$(document).ready(function () {
    moment.locale('es');
    
    // Variables para almacenar filtros activos
    let filtrosActivos = {
        anio: '',
        tipo: '',
        estado: ''
    };
    
    let tabla = null;
    let csrfToken = "<?= csrf_hash() ?>";
    let csrfName = "<?= csrf_token() ?>";

    // Mostrar/Ocultar loading
    const showLoading = () => $('#loadingOverlay').fadeIn(300);
    const hideLoading = () => $('#loadingOverlay').fadeOut(300);

    // Mostrar/Ocultar mensaje de no datos
    const toggleNoDataMessage = (show) => {
        if (show) {
            $('#noDataMessage').show();
        } else {
            $('#noDataMessage').hide();
        }
    };

    // Inicializar DataTable
    const inicializarTabla = () => {
        if (tabla) {
            tabla.destroy();
        }
        
        tabla = $('#tablaLotes').DataTable({
            language: { 
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" 
            },
            responsive: true,
            pageLength: 25,
            lengthMenu: [10, 25, 50, 100],
            order: [[0, 'desc']],
            dom: '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
            columns: [
                { data: 'id', className: 'fw-bold' },
                { data: 'folio' },
                { data: 'fecha' },
                { data: 'centro' },
                { data: 'tipo_pimienta' },
                { data: 'tipo_entrada' },
                { data: 'proveedor' },
                { data: 'peso', className: 'text-end fw-semibold' },
                { data: 'precio', className: 'text-end' },
                { data: 'total', className: 'text-end fw-bold text-success' },
                { data: 'estado' },
                { data: 'acciones', className: 'text-center' }
            ],
            initComplete: function() {
                // Agregar búsqueda rápida
                $('#tableSearch').on('keyup', function() {
                    tabla.search(this.value).draw();
                });
            },
            drawCallback: function(settings) {
                // Mostrar/ocultar mensaje de no datos
                if (settings.fnRecordsDisplay() === 0) {
                    toggleNoDataMessage(true);
                } else {
                    toggleNoDataMessage(false);
                }
                
                // Agregar animación a las filas
                $('.custom-table tbody tr').each(function(i) {
                    $(this).delay(i * 50).queue(function() {
                        $(this).css('opacity', '1').dequeue();
                    });
                });
            },
            createdRow: function(row, data, dataIndex) {
                $(row).css('opacity', '0');
            }
        });
    };

    inicializarTabla();

    // Actualizar filtros activos en la UI
    const actualizarFiltrosUI = () => {
        const badgesContainer = $('#filtersBadges');
        badgesContainer.empty();
        
        let hasActiveFilters = false;
        
        // Año
        if (filtrosActivos.anio) {
            hasActiveFilters = true;
            badgesContainer.append(`
                <span class="filter-badge" data-filter="anio">
                    Año: ${filtrosActivos.anio}
                    <button type="button" class="remove-filter" data-filter="anio" title="Quitar filtro">
                        <i class="bi bi-x"></i>
                    </button>
                </span>
            `);
        }
        
        // Tipo
        if (filtrosActivos.tipo) {
            hasActiveFilters = true;
            badgesContainer.append(`
                <span class="filter-badge" data-filter="tipo">
                    Tipo: ${filtrosActivos.tipo}
                    <button type="button" class="remove-filter" data-filter="tipo" title="Quitar filtro">
                        <i class="bi bi-x"></i>
                    </button>
                </span>
            `);
        }
        
        // Estado
        if (filtrosActivos.estado) {
            hasActiveFilters = true;
            badgesContainer.append(`
                <span class="filter-badge" data-filter="estado">
                    Estado: ${filtrosActivos.estado}
                    <button type="button" class="remove-filter" data-filter="estado" title="Quitar filtro">
                        <i class="bi bi-x"></i>
                    </button>
                </span>
            `);
        }
        
        // Mostrar/ocultar contenedor de filtros activos
        if (hasActiveFilters) {
            $('#activeFiltersContainer').slideDown();
        } else {
            $('#activeFiltersContainer').slideUp();
        }
        
        // Asignar eventos a los botones de eliminar filtro
        $('.remove-filter').off('click').on('click', function() {
            const filterType = $(this).data('filter');
            removerFiltro(filterType);
        });
    };

    // Función para remover filtros
    const removerFiltro = (filtro) => {
        switch(filtro) {
            case 'anio':
                $('#filtroAnio').val('');
                filtrosActivos.anio = '';
                break;
            case 'tipo':
                $('#filtroTipo').val('');
                filtrosActivos.tipo = '';
                break;
            case 'estado':
                $('#filtroEstado').val('');
                filtrosActivos.estado = '';
                break;
        }
        cargarDatos();
    };

    // Cargar datos con filtros
    const cargarDatos = () => {
        showLoading();
        
        $.ajax({
            url: "<?= base_url('lotes-entrada/apiEntradas') ?>",
            type: "GET",
            data: {
                anio: filtrosActivos.anio || '',
                tipo: filtrosActivos.tipo || '',
                estado: filtrosActivos.estado || ''
            },
            dataType: "json",
            success: (res) => {
                tabla.clear();

                if (res.data && res.data.length > 0) {
                    const datosFormateados = res.data.map(item => {
                        return {
                            id: item.id,
                            folio: `<span class="badge bg-primary rounded-pill px-3" style="background: linear-gradient(135deg, #4CAF50, #2E7D32) !important; border: none;">${item.folio || 'N/A'}</span>`,
                            fecha: item.fecha_entrada ? 
                                `<div class="text-nowrap fw-semibold">${moment(item.fecha_entrada).format('DD/MM/YYYY')}</div>
                                 <small class="text-muted">${moment(item.fecha_entrada).format('HH:mm')}</small>` : '-',
                            centro: `<span class="fw-bold" style="color: var(--primary-green-dark)">${item.centro || '-'}</span>`,
                            tipo_pimienta: item.tipo_pimienta || '-',
                            tipo_entrada: `<span class="badge-custom ${getTipoBadgeClass(item.tipo_entrada)}">
                                <i class="bi ${getTipoIcon(item.tipo_entrada)} me-1"></i>${item.tipo_entrada || 'Sin tipo'}
                            </span>`,
                            proveedor: item.proveedor ? 
                                `<div class="text-truncate" style="max-width: 150px;" title="${item.proveedor}">
                                    <i class="bi bi-person-circle me-1 text-muted"></i>${item.proveedor}
                                </div>` : '-',
                            peso: item.peso_bruto_kg ? 
                                `<span class="fw-bold" style="color: var(--primary-green)">${parseFloat(item.peso_bruto_kg).toFixed(2)}</span> kg` : '-',
                            precio: item.precio_compra ? 
                                `<span class="text-success">$${parseFloat(item.precio_compra).toFixed(2)}</span>` : '-',
                            total: item.costo_total ? 
                                `<span class="fw-bold" style="color: var(--primary-green-dark)">$${parseFloat(item.costo_total).toFixed(2)}</span>` : '-',
                            estado: `<span class="badge-custom ${getEstadoBadgeClass(item.estado)}">
                                <i class="bi ${getEstadoIcon(item.estado)} me-1"></i>${item.estado || 'Desconocido'}
                            </span>`,
                            acciones: generarBotonesAccion(item.id, item.estado)
                        };
                    });

                    tabla.rows.add(datosFormateados).draw();
                    
                    // Actualizar contador
                    const count = res.data.length;
                    $('#contadorRegistros').text(count)
                        .css('color', count > 0 ? 'var(--primary-green)' : 'var(--neutral-gray)');
                    
                    actualizarFiltrosUI();
                    
                    // Animación para el contador
                    $('#contadorRegistros').css('transform', 'scale(1.1)');
                    setTimeout(() => {
                        $('#contadorRegistros').css('transform', 'scale(1)');
                    }, 300);
                } else {
                    tabla.clear().draw();
                    $('#contadorRegistros').text('0').css('color', 'var(--neutral-gray)');
                    actualizarFiltrosUI();
                    
                    // Mostrar mensaje solo si hay filtros activos
                    if (filtrosActivos.anio || filtrosActivos.tipo || filtrosActivos.estado) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Sin resultados',
                            text: 'No se encontraron registros con los filtros seleccionados.',
                            timer: 2000,
                            showConfirmButton: false,
                            background: '#FAFAFA',
                            color: '#37474F'
                        });
                    }
                }
            },
            error: (xhr) => {
                console.error('Error:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudieron cargar los datos. Intente nuevamente.',
                    background: '#FFEBEE',
                    color: '#C62828'
                });
            },
            complete: () => {
                hideLoading();
            }
        });
    };

    // Funciones auxiliares
    const getTipoBadgeClass = (tipo) => {
        const tipos = {
            'Compra': 'badge-compra',
            'Transferencia': 'badge-transferencia',
            'Devolución': 'badge-devolucion',
            'Ajuste': 'badge-ajuste'
        };
        return tipos[tipo] || 'badge-secondary';
    };

    const getTipoIcon = (tipo) => {
        const icons = {
            'Compra': 'bi-cart-check',
            'Transferencia': 'bi-arrow-left-right',
            'Devolución': 'bi-arrow-return-left',
            'Ajuste': 'bi-sliders'
        };
        return icons[tipo] || 'bi-question-circle';
    };

    const getEstadoBadgeClass = (estado) => {
        const estados = {
            'Activo': 'badge-activo',
            'Recibido': 'badge-activo',
            'Pendiente': 'badge-pendiente',
            'Cancelado': 'badge-cancelado',
            'Procesado': 'badge-procesado'
        };
        return estados[estado] || 'badge-secondary';
    };

    const getEstadoIcon = (estado) => {
        const icons = {
            'Activo': 'bi-check-circle',
            'Recibido': 'bi-check-circle',
            'Pendiente': 'bi-clock',
            'Cancelado': 'bi-x-circle',
            'Procesado': 'bi-gear'
        };
        return icons[estado] || 'bi-question-circle';
    };

    const generarBotonesAccion = (id, estado) => {
        const puedeEliminar = estado !== 'Procesado' && estado !== 'Cancelado';
        
        return `
            <div class="action-buttons">
                <a href="<?= base_url('lotes-entrada/edit/') ?>${id}" 
                   class="btn-action btn-edit" 
                   title="Editar lote">
                    <i class="bi bi-pencil"></i>
                </a>
                ${puedeEliminar ? `
                <button type="button" onclick="eliminarLote(${id})" 
                        class="btn-action btn-delete" 
                        title="Eliminar lote">
                    <i class="bi bi-trash"></i>
                </button>
                ` : ''}
                <a href="<?= base_url('lotes-entrada/lotePDF/') ?>${id}" 
                   target="_blank" 
                   class="btn-action btn-pdf" 
                   title="Ver PDF">
                    <i class="bi bi-file-earmark-pdf"></i>
                </a>
                <a href="<?= base_url('lotes-entrada/show/') ?>${id}" 
                   class="btn-action btn-view" 
                   title="Ver detalles">
                    <i class="bi bi-eye"></i>
                </a>
            </div>
        `;
    };

    // Función para eliminar lote
    window.eliminarLote = (id) => {
        Swal.fire({
            title: '<span style="color: var(--primary-green-dark)">¿Eliminar este lote?</span>',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#D32F2F',
            cancelButtonColor: '#757575',
            confirmButtonText: '<i class="bi bi-trash me-1"></i>Sí, eliminar',
            cancelButtonText: '<i class="bi bi-x-circle me-1"></i>Cancelar',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: "<?= base_url('lotes-entrada/delete/') ?>" + id,
                    type: "POST",
                    data: {
                        [csrfName]: csrfToken
                    },
                    dataType: "json"
                }).then(response => {
                    if (!response.success) {
                        throw new Error(response.message || 'Error al eliminar');
                    }
                    return response;
                }).catch(error => {
                    Swal.showValidationMessage(
                        `Error: ${error.responseJSON?.message || error.statusText}`
                    );
                });
            },
            allowOutsideClick: () => !Swal.isLoading(),
            background: '#FFF8E1',
            color: '#37474F'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: '<span style="color: var(--primary-green)">¡Eliminado!</span>',
                    text: 'El lote ha sido eliminado correctamente.',
                    timer: 1500,
                    showConfirmButton: false,
                    background: '#F1F8E9',
                    color: '#37474F'
                });
                cargarDatos();
            }
        });
    };

    // Aplicar filtros
    $('#btnAplicarFiltros').click(() => {
        filtrosActivos.anio = $('#filtroAnio').val();
        filtrosActivos.tipo = $('#filtroTipo').val();
        filtrosActivos.estado = $('#filtroEstado').val();
        cargarDatos();
    });

    // Resetear todos los filtros
    $('#btnResetFiltros, #btnClearAllFilters').click(() => {
        $('#filtroAnio').val('');
        $('#filtroTipo').val('');
        $('#filtroEstado').val('');
        filtrosActivos.anio = '';
        filtrosActivos.tipo = '';
        filtrosActivos.estado = '';
        cargarDatos();
        
        // Animación de reset
        $('.filter-input').css('border-color', '#FF9800');
        setTimeout(() => {
            $('.filter-input').css('border-color', '#E0E0E0');
        }, 500);
    });

    // Búsqueda por folio
    $('#btnBuscarFolioPDF').click(() => {
        const folio = $('#buscarFolioInput').val().trim();
        
        if (!folio) {
            Swal.fire({
                icon: 'warning',
                title: 'Folio requerido',
                text: 'Por favor ingrese un número de folio',
                timer: 2000,
                showConfirmButton: false,
                background: '#FFF3E0',
                color: '#FF6F00'
            });
            return;
        }
        
        // Animación del botón
        const btn = $('#btnBuscarFolioPDF');
        const originalHtml = btn.html();
        btn.html('<i class="bi bi-search me-1"></i>Buscando...').prop('disabled', true);
        
        // Abrir PDF del folio específico
        const url = `<?= base_url('lotes-entrada/pdfFolio') ?>?folio=${folio}`;
        const newWindow = window.open(url, '_blank');
        
        setTimeout(() => {
            btn.html(originalHtml).prop('disabled', false);
            $('#buscarFolioInput').val('');
        }, 1000);
    });

    // Exportar PDF con filtros aplicados
    $('#btnExportarPDF').click(() => {
        let url = "<?= base_url('reportes/entradasPdf') ?>";
        
        // Construir URL con filtros
        const tipo = filtrosActivos.tipo || 'all';
        const entrada = 'all';
        const anio = filtrosActivos.anio || 'all';
        
        url += `/${tipo}/${entrada}/${anio}`;
        
        window.open(url, '_blank');
    });

    // Exportar Excel con filtros aplicados
    $('#btnExportarExcel').click(() => {
        let url = "<?= base_url('reportes/entradasExcel') ?>";
        
        // Construir URL con filtros
        const tipo = filtrosActivos.tipo || 'all';
        const entrada = 'all';
        const anio = filtrosActivos.anio || 'all';
        
        url += `/${tipo}/${entrada}/${anio}`;
        
        window.location.href = url;
    });

    // Actualizar datos
    $('#btnRefresh').click(function () {
        const btn = $(this);
        const originalHtml = btn.html();
        
        btn.prop('disabled', true)
           .html('<span class="spinner-border spinner-border-sm me-2"></span>Actualizando...');
        
        cargarDatos();
        
        setTimeout(() => {
            btn.prop('disabled', false).html(originalHtml);
            Swal.fire({
                icon: 'success',
                title: '<span style="color: var(--primary-green)">¡Actualizado!</span>',
                text: 'Los datos se han actualizado correctamente.',
                timer: 1500,
                showConfirmButton: false,
                background: '#F1F8E9',
                color: '#37474F'
            });
        }, 1000);
    });

    // Enter en inputs de filtro
    $('.filter-input, .folio-search-input').keypress(function(e) {
        if(e.which === 13) {
            if ($(this).is('#buscarFolioInput')) {
                $('#btnBuscarFolioPDF').click();
            } else {
                $('#btnAplicarFiltros').click();
            }
        }
    });

    // Animación de carga inicial
    setTimeout(() => {
        $('.page-container').css('opacity', '1');
    }, 100);

    // Cargar datos iniciales
    cargarDatos();
});
</script>

<?= $this->endSection() ?>