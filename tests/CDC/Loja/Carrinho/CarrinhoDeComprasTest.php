<?php

namespace CDC\Loja\Carrinho;

use CDC\Loja\Test\TestCase,
    CDC\Loja\Carrinho\CarrinhoDeCompras,
    CDC\Loja\Produto\Produto;

class CarrinhoDeComprasTest extends TestCase
{
    private $carrinho;

    protected function setUp()
    {
        $this->carrinho = new CarrinhoDeCompras();
        parent::setUp();
    }

    public function testDeveRetornarZeroSeCarrinhoVazio()
    {
        $valor = $this->carrinho->maiorValor();
        $this->assertEquals(0, $valor, null, 0.0001);
    }

    public function testDeveRetornarValorDoItemSeCarrinhoCom1Elemento()
    {
        $this->carrinho->adiciona(new Produto("Geladeira", 1, 900.00));

        $valor = $this->carrinho->maiorValor();

        $this->assertEquals(900.00, $valor, null, 0.0001);
    }

    public function testDeveRetornarMaiorValorSeCarrinhoComMuitosElementos()
    {
        $this->carrinho->adiciona(new Produto("Fogao", 1, 1500.00));
        $this->carrinho->adiciona(new Produto("Geladeira", 1, 900.00));
        $this->carrinho->adiciona(new Produto("Maquina de lavar", 1, 750.00));

        $valor = $this->carrinho->maiorValor();

        $this->assertEquals(1500.00, $valor, null, 0.0001);
    }

    public function testDeveAdicionarItens()
    {
        //garante que o carrinho está vazio
        $this->assertEmpty($this->carrinho->getProdutos());

        $produto = new Produto("Geladeira", 900.0, 1);
        $this->carrinho->adiciona($produto);

        $esperado = count($this->carrinho->getProdutos());
        $this->assertEquals(1, $esperado);
        $this->assertEquals($produto, $this->carrinho->getProdutos()[0]);
    }

    public function testListaDeProdutos()
    {
        $lista = (new CarrinhoDeCompras())
            ->adiciona(new Produto("Jogo de Jantar", 200.00, 1))
            ->adiciona(new Produto("Jogo de Pratos", 100.00, 1));

        $this->assertEquals(2, count($lista->getProdutos()));
        $this->assertEquals(200.0, $lista->getProdutos()[0]->getValorUnitario());
        $this->assertEquals(100.0, $lista->getProdutos()[1]->getValorUnitario());
    }
}
