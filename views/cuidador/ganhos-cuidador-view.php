<div id="conteudo-2" class="content-section">
    <h2>Meus Ganhos</h2>
    <div class="ganhos-container">
        <div class="ganhos-resumo">
            <h3>Total de Ganhos: R$ <span id="totalGanhos"><?php echo $totalGanhosFormatado; ?></span></h3>
        </div>

        <?php
        foreach ($mesesAno as $mesNumero => $mesNome) {
            if ($valores[$mesNumero] > 0) { // Só mostra meses com ganhos
                echo "<div class='ganho'>";
                echo "<p><span class='material-symbols-outlined'>calendar_month</span> Mês: <span class='info-label'>" . htmlspecialchars($mesNome) . "</span></p>";
                echo "<p><span class='material-symbols-outlined'>attach_money</span> Valor: <span class='info-label'>R$ " . number_format($valores[$mesNumero], 2, ',', '.') . "</span></p>";
                echo "</div>";
            }
        }
        ?>
        <canvas id="ganhosChart"></canvas>
    </div>
    <script src="/views/cuidador/main.js" type="module"></script>
</div>