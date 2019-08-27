<?php
/**
 * calc_ipv4 - Cálculo de máscara de sub-rede IPv4
 */
class calc_ipv4
{
    // O endereço IP
    public $endereco;
    
    // O cidr
    public $cidr;
    
    // O endereço IP 
    public $endereco_completo;

    /**
     * O construtor apenas configura as propriedades da classe
     */
    public function __construct( $endereco_completo ) {
        $this->endereco_completo = $endereco_completo;
        $this->valida_endereco();
    }
    


    /**
     * Valida o endereço IPv4
     */
    public function valida_endereco() {
        // Expressão regular
        $regexp = '/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\/[0-9]{1,2}$/';

        // Verifica o IP/CIDR
        if ( ! preg_match( $regexp, $this->endereco_completo ) ) {
            return false;
        }
        
        // Separa o IP do prefixo CIDR
        $endereco = explode( '/', $this->endereco_completo );
        
        // CIDR
        $this->cidr = (int) $endereco[1];
        
        // Endereço IPv4
        $this->endereco = $endereco[0];
        
        // Verifica o prefixo
        if ( $this->cidr > 32 ) {
            return false;
        }
        
        // Faz um loop e verifica cada número do IP
        foreach( explode( '.', $this->endereco ) as $numero ) {
        
            // Garante que é um número
            $numero = (int) $numero;
            
            // Não pode ser maior que 255 nem menor que 0
            if ( $numero > 255 || $numero < 0 ) {
                return false;
            }
        }
        
        // IP "válido" (correto)
        return true;
    }



    /* Retorna o endereço IPv4/CIDR */
    public function endereco_completo() { 
        return ( $this->endereco_completo ); 
    }



    /* Retorna o endereço IPv4 */
    public function endereco() { 
        return ( $this->endereco ); 
    }



    /* Retorna o prefixo CIDR */
    public function cidr() { 
        return ( $this->cidr ); 
    }



    /* Retorna a máscara de sub-rede */
    public function mascara() {
        if ( $this->cidr() == 0 ) {
            return '0.0.0.0';
        }

        return ( 
            long2ip(
                ip2long("255.255.255.255") << ( 32 - $this->cidr ) 
            )
        );
    }



    /* Retorna a rede na qual o IP está */
    public function rede() {
        if ( $this->cidr() == 0 ) {
            return '0.0.0.0';
        }

        return (
            long2ip( 
                ( ip2long( $this->endereco ) ) & ( ip2long( $this->mascara() ) )
            )
        );
    }



    /* Retorna o IP de broadcast da rede */
    public function broadcast() {
        if ( $this->cidr() == 0 ) {
            return '255.255.255.255';
        }
        
        return (
            long2ip( ip2long($this->rede() ) | ( ~ ( ip2long( $this->mascara() ) ) ) )
        );
    }


    
    /* Retorna o número total de IPs (com a rede e o broadcast) */
    public function total_ips() {
        return( pow(2, ( 32 - $this->cidr() ) ) );
    }
    


    /* Retorna os número de IPs que podem ser utilizados na rede */
    public function ips_rede() {
        if ( $this->cidr() == 32 ) {
            return 0;
        } elseif ( $this->cidr() == 31 ) {
            return 0;
        }
        
        return( abs( $this->total_ips() - 2 ) );
    }
    


    /* Retorna os número de IPs que podem ser utilizados na rede */
    public function primeiro_ip() {
        if ( $this->cidr() == 32 ) {
            return null;
        } elseif ( $this->cidr() == 31 ) {
            return null;
        } elseif ( $this->cidr() == 0 ) {
            return '0.0.0.1';
        }
        
        return (
            long2ip( ip2long( $this->rede() ) | 1 )
        );
    }


    
    /* Retorna os número de IPs que podem ser utilizados na rede */
    public function ultimo_ip() {
        if ( $this->cidr() == 32 ) {
            return null;
        } elseif ( $this->cidr() == 31 ) {
            return null;
        }
    
        return (
            long2ip( ip2long( $this->rede() ) | ( ( ~ ( ip2long( $this->mascara() ) ) ) - 1 ) )
        );
    }


    /* Diz se o ip é público ou privado */
    public function publicoPrivado(){

        $endereco_separado = explode('.',$this->endereco);

        if ($endereco_separado[0] == 10 OR $endereco_separado[0] == 172 AND $endereco_separado[1] >= 16 AND $endereco_separado[1] <= 31 OR $endereco_separado[0] == 192 AND $endereco_separado[1] == 168) {
            return "Privado";
        }else{
            return "Público";
        }
   
    }



    /* Diz a classe do IP */
    public function classe_ip(){
        $enderecoExplode = explode(".", $this->endereco);

        if($enderecoExplode[0] <= 127){
            $classe = "A";

        }elseif(128 <= $enderecoExplode[0] && $enderecoExplode[0] <= 191){
            $classe = "B";

        }elseif(192 <= $enderecoExplode[0] && $enderecoExplode[0] <= 223){
            $classe = "C";

        }elseif(224 <= $enderecoExplode[0] && $enderecoExplode[0] <= 239){
            $classe = "D";

        }elseif(240 <= $enderecoExplode[0] && $enderecoExplode[0] <= 255){
            $classe = "E";
        }

        return $classe;
    }



    /* Transforma o IP em binário */
    public function ip_binario(){
        $enderecoExplode = explode("." , $this->endereco);

        $binario = ''; 

        foreach($enderecoExplode as $pedaço){
            $bin[] = decbin($pedaço);

            $binario = implode('.', $bin);
        }

        return $binario;
    }


    /* Informa a quantidade de sub-redes */
    public function qtd_subredes(){
        $teste = $this->total_ips();
        $quant = (256 / $teste);
        return $quant;
    }


    /* Informa cada bloco de sub-rede */
    public function blocoSubrede(){

        $count = $this->qtd_subredes();

        $broadcast = explode(".", $this->broadcast());
        $endereco = explode(".", $this->endereco());

        //ip sem o ultimo número
        $semi_ip = $broadcast[0] . "." . $broadcast[1] . "." . $broadcast[2];

        
        $host1 = 0;

    
        if($this->cidr() <= 30){

            for ($i=0; $i < $count; $i++) { 
                
                
                $host2 = $host1 + $broadcast[3];

                //primeiro ip não diponível
                $primeiroValor = $semi_ip . "." . $host1;

                //ultimo ip não disponível
                $segundoValor = $semi_ip . "." . $host2;

                $intervalo1 = $host1 + 1;
                $intervalo2 = $host2 - 1;

                //primeiro ip diponível
                $valorIntervalo1 = $semi_ip . "." . $intervalo1;
                //ultimo ip disponível
                $valorIntervalo2 = $semi_ip . "." . $intervalo2;

                //confere em qual intervalo ta o endereço
                if($endereco[3] >= $intervalo1 and $endereco[3] <= $intervalo2 ){
                    $mensagem[$i] = "<b style='color: #fd5708;'>" . $primeiroValor . '</b> -- <b>' . $valorIntervalo1 . ' - <b style="color: #fd5708;">' . $this->endereco . '</b> - ' . $valorIntervalo2 . '</b> -- <b style="color: #fd5708;">' . $segundoValor . "</b> <br>";
                }else{
                    $mensagem[$i] = "<b style='color: #fd5708;'>" . $primeiroValor . '</b> -- <b>' . $valorIntervalo1 . ' - ' . $valorIntervalo2 . '</b> -- <b style="color: #fd5708;">' . $segundoValor . "</b> <br>";
                }

                $host1 = $host1 + $broadcast[3] + 1;

            }

        }elseif( $this->cidr() == 31 ){

            for ($i=0; $i < $count; $i++) { 
                            
                $host2 = $host1 + $broadcast[3];

                $primeiroValor = $semi_ip . "." . $host1;
                $segundoValor = $semi_ip . "." . $host2;

                $intervalo1 = $host1 + 1;
                $intervalo2 = $host2 - 1;

                $valorIntervalo1 = $semi_ip . "." . $intervalo1;
                $valorIntervalo2 = $semi_ip . "." . $intervalo2;

                if($endereco[3] >= $intervalo1 and $endereco[3] <= $intervalo2 ){
                    $mensagem[$i] = "<b style='color: #fd5708;'>" . $primeiroValor . '</b> -- <b>' . $valorIntervalo1 . ' - <b style="color: #fd5708;">' . $this->endereco . '</b> - ' . $valorIntervalo2 . '</b> -- <b style="color: #fd5708;">' . $segundoValor . "</b> <br>";
                }else{
                    $mensagem[$i] = "<b style='color: #fd5708;'>" . $primeiroValor . '</b> -- <b>' . $valorIntervalo1 . ' - ' . $valorIntervalo2 . '</b> -- <b style="color: #fd5708;">' . $segundoValor . "</b> <br>";
                }

                $host1 = $host1 + $broadcast[3] + 1;

            }
            
        }elseif( $this->cidr() == 32 ){

            for ($i=0; $i < $count; $i++) { 

                $Valor = $semi_ip . "." . $host1;

                if($endereco[3] == $host1 ){
                    $mensagem[$i] = "<b style='color: #fd5708;'>" . $Valor . '</b> -- <b>' . $Valor . ' - <b style="color: #fd5708;">' . $this->endereco . '</b> - ' . $Valor . '</b> -- <b style="color: #fd5708;">' . $Valor . "</b> <br>";
                }else{
                    $mensagem[$i] = "<b style='color: #fd5708;'>" . $Valor . '</b> -- <b>' . $Valor . ' - ' . $Valor . '</b> -- <b style="color: #fd5708;">' . $Valor . "</b> <br>";
                }
                
                $host1 = $host1 + $broadcast[3];

            }
        }

        return $mensagem;   
    }
}

    