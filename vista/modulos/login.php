<?php
include_once "vista/modulos/1cabesera.php";
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card p-4 shadow login-card" style="max-width: 600px; width: 100%;">
        <div class="card-body text-center">
            
            <img src="vista/css/img/logo teatro.png" alt="Logo Taquillería del Sol" class="login-logo mb-3">

            <h1 class="card-title mb-4">Taquillería del Sol</h1>
            <p class="text-muted mb-4">Inicia sesión según tu tipo de cuenta</p>

            <ul class="nav nav-pills nav-fill mb-4" id="accountTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="empresa-tab" data-bs-toggle="tab" data-bs-target="#empresa" type="button" role="tab" aria-selected="false">
                        <i class="fas fa-building me-1"></i> Empresa
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="usuario-tab" data-bs-toggle="tab" data-bs-target="#usuario" type="button" role="tab" aria-selected="true">
                        <i class="fas fa-user me-1"></i> Usuarios
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="admin-tab" data-bs-toggle="tab" data-bs-target="#administrador" type="button" role="tab" aria-selected="false">
                        <i class="fas fa-cog me-1"></i> Administrador
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="accountTabsContent">
                
                <div class="tab-pane fade show active" id="usuario" role="tabpanel" aria-labelledby="usuario-tab">
                    <?php include_once "vista/modulos/Auth/formUsuario.php"; ?>
                </div>
                
                <div class="tab-pane fade" id="empresa" role="tabpanel" aria-labelledby="empresa-tab">
                    <?php include_once "vista/modulos/Auth/formEmpresa.php"; ?>
                </div>
                
                <div class="tab-pane fade" id="administrador" role="tabpanel" aria-labelledby="admin-tab">
                    <?php include_once "vista/modulos/Auth/formAdmin.php"; ?>
                </div>

            </div>
        </div>
    </div>
</div>