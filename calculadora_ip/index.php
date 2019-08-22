<?php include 'classes/funcoes.php'; ?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="shortcut icon" href="imagens/calcipicon.ico" />
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>Calculadora de IP</title>
    </head>
    <body>

        <h1 class="h1">Calculadora de</h1><h1 class="h1IP"> IP </h1>

        <div class="divider"></div>

        <img src="imagens/calcip.png" id="calculadora_centro">
        
        <form method="post" class="form">
            <label for="#">Endereço IP / Prefixo CIDR: <small style="color: #fd5708;"> (Não esqueça de utilizar a máscara!)</small></label>
                <div class="divider"></div>

            <input type="text" class="form-control input_text" name="ip" value="<?php echo @$_POST['ip'];?>" placeholder="Ex.: 192.168.0.1/24">
                <div class="divider"></div>
                
            <input type="submit" class="input_submit" value="Calcular">
        </form>

        <div class="resultado2">

            <?php
                if ( $_SERVER['REQUEST_METHOD'] === 'POST' && ! empty( $_POST['ip'] ) ) {
                    $ip = new calc_ipv4( $_POST['ip'] );

                    if( $ip->valida_endereco() ) {
                        echo '<h2>Configurações de rede para <span style="color: #fd5708;">' . $_POST['ip'] . '</span> </h2>';
                        echo "<pre class='resultado'>";
                        
                        echo "<b>Endereço/Rede: </b>" . $ip->endereco_completo() . '<br>';
                        echo "<b>Máscara de sub-rede: </b>" . $ip->mascara() . '<br>';
                        echo "<b>IP da Rede: </b>" . $ip->rede() . '/' . $ip->cidr() . '<br>';
                        echo "<b>Broadcast da Rede: </b>" . $ip->broadcast() . '<br>';
                        echo "<b>Primeiro Host: </b>" . $ip->primeiro_ip() . '<br>';
                        echo "<b>Último Host: </b>" . $ip->ultimo_ip() . '<br>';
                        echo "<b>Total de IPs:  </b>" . $ip->total_ips() . '<br>';
                        echo "<b>Binário: </b>" . $ip->ip_binario() . '<br>';
                        echo "<b>Hosts: </b>" . $ip->ips_rede() . '<br>';
                        echo "<b>Sub-redes: </b>" . $ip->qtd_subredes() . '<br>';
                        echo "<b>Classe: </b>" . $ip->classe_ip() . '<br>';
                        echo "<b>Público/Privado: </b>" . $ip->publicoPrivado();
                        echo "</pre>";
                    } else {
                        echo 'Endereço IPv4 inválido!';
                    }
                }
            ?>
        </div>

        <div class="resultado3">

            <?php
                if ( $_SERVER['REQUEST_METHOD'] === 'POST' && ! empty( $_POST['ip'] ) ) {

                    if( $ip->valida_endereco() ) {
                        echo '<h2> <span style="color: #fd5708;">' . $_POST['ip'] . '</span> </h2>';
                        echo "<pre class='resultado'>";
                        
                        echo "<b>Endereço/Rede: </b>" . $ip->endereco_completo() . '<br>';
                        echo "<b>Máscara de sub-rede: </b>" . $ip->mascara() . '<br>';
                        echo "<b>IP da Rede: </b>" . $ip->rede() . '/' . $ip->cidr() . '<br>';
                        echo "<b>Broadcast da Rede: </b>" . $ip->broadcast() . '<br>';
                        echo "<b>Primeiro Host: </b>" . $ip->primeiro_ip() . '<br>';
                        echo "<b>Último Host: </b>" . $ip->ultimo_ip() . '<br>';
                        echo "<b>Total de IPs:  </b>" . $ip->total_ips() . '<br>';
                        echo "<b>Binário: </b>" . $ip->ip_binario() . '<br>';
                        echo "<b>Hosts: </b>" . $ip->ips_rede() . '<br>';
                        echo "<b>Sub-redes: </b>" . $ip->qtd_subredes() . '<br>';
                        echo "<b>Classe: </b>" . $ip->classe_ip() . '<br>';
                        echo "<b>Público/Privado: </b>" . $ip->publicoPrivado();
                        echo "</pre>";
                    } else {
                        echo 'Endereço IPv4 inválido!';
                    }
                }
            ?>
        </div>


    </body>
    <footer>

    </footer>
</html>
