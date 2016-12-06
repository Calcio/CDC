<?php

namespace CDC\Loja\FluxoDeCaixa;

use CDC\Loja\Test\TestCase,
    CDC\Loja\FluxoDeCaixa\GeradorDeNotaFiscal,
    CDC\Loja\FluxoDeCaixa\Pedido;

use CDC\Loja\FluxoDeCaixa\NFDao;

use CDC\Exemplos\RelogioDoSistema;
use CDC\Loja\Tributos\TabelaInterface;
    // ,
    // CDC\Loja\FluxoDeCaixa\Pedido,
    // CDC\Loja\FluxoDeCaixa\NFDao,
    // CDC\Loja\FluxoDeCaixa\SAP;

use Mockery;

class GeradorDeNotaFiscalTest extends TestCase
{
    public function testDeveInvocarAcoesPosteriores()
    {
        $acao1 = Mockery::mock('CDC\Loja\FluxoDeCaixa\AcaoAposGerarNotaInterface');
        $acao1->shouldReceive('executa')->andReturn(true);

        $acao2 = Mockery::mock('CDC\Loja\FluxoDeCaixa\AcaoAposGerarNotaInterface');
        $acao2->shouldReceive('executa')->andReturn(true);

        // mockando uma tabela, que ainda nem existe
        $tabela = Mockery::mock('CDC\Loja\Tributos\Tabela');
        // definindo o futuro comportamento "paraValor", que deve
        // retornar 0.2 caso valor seja 1000.0
        $tabela->shouldReceive('paraValor')->with(1000.0)->andReturn(0.2);

        $gerador = new GeradorDeNotaFiscal([$acao1, $acao2], new RelogioDoSistema(), $tabela);
        //, new RelogioDoSistema()
        $pedido = new Pedido("Andre", 1000, 1);

        $nf = $gerador->gera($pedido);

        $this->assertTrue($acao1->executa($nf));
        $this->assertTrue($acao2->executa($nf));
        $this->assertNotNull($nf);
        $this->assertInstanceOf('CDC\Loja\FluxoDeCaixa\NotaFiscal', $nf);
    }

    public function testDeveConsultarATabelaParaCalcularValor()
    {
        // mockando uma tabela, que ainda nem existe
        $tabela = Mockery::mock('CDC\Loja\Tributos\Tabela');

        // definindo o futuro comportamento "paraValor", que deve
        // retornar 0.2 caso valor seja 1000.0
        $tabela->shouldReceive('paraValor')->with(1000.0)->andReturn(0.2);

        $gerador = new GeradorDeNotaFiscal(array(), new RelogioDoSistema(), $tabela);
        $pedido = new Pedido("Andre", 1000.0, 1);

        $nf = $gerador->gera($pedido);

        // garantindo que a tabela foi consultada
        $this->assertEquals(1000 * 0.2, $nf->getValor(), null, 0.00001);
    }

    // public function testDeveGerarNFComValorDeImpostosDescontado()
    // {
    //     // mockando uma tabela, que ainda nem existe
    //     $tabela = Mockery::mock('CDC\Loja\Tributos\Tabela');
    //     // definindo o futuro comportamento "paraValor", que deve
    //     // retornar 0.2 caso valor seja 1000.0
    //     $tabela->shouldReceive('paraValor')->with(1000.0)->andReturn(0.2);
    //
    //     $gerador = new GeradorDeNotaFiscal(array(), new RelogioDoSistema(), $tabela);
    //     $pedido = new Pedido("Andre", 1000, 1);
    //
    //     $nf = $gerador->gera($pedido);
    //
    //     $this->assertEquals(1000 * 0.94, $nf->getValor(), null, 0.00001);
    // }
}
