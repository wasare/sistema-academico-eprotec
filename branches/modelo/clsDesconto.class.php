<?php
require_once("../controle/gtiConexao.class.php");
require_once("clsRefeicao.class.php");
require_once("clsGrupo.class.php");

class clsDesconto
{
	public function clsDesconto()
	{
	}
	
	//METODOS
	
	public function ListaRefeicaoDesconto($codgrupo)
	{
		$SQL = 'SELECT * FROM "prato"."tb_refeicao";';
		
		$con = new gtiConexao();
		$con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);
		
		$tabela = "";
		$linha = "";
		
		foreach($tbl as $chave => $linha)
		{
            
			$codref = $linha['cod_refeicao'];
			$desref = htmlentities($linha['des_refeicao']);
			
			$desconto = $this->PegaDesconto($codgrupo, $codref);
									  
			$linha = '<tr>';
			$linha .= '<th width="30%" scope="col"><div align="left">'.$desref.'</div></th>';
			$linha .= '<th width="70%" scope="col"><div align="left">';
			$linha .= '<input name="txtDesconto'.$codref.'" type="text" class="caixaPequena" id="txtDesconto'.$codref.'" value="'.$desconto.'"/><b>&nbsp;%</b>';
			$linha .= '</div></th>';
		  	$linha .= '</tr>';
                                  
			$tabela = $tabela . $linha;
		}		
		
		
		$con->gtiDesconecta();
		
		return $tabela;
	}
	
	public function PegaDesconto($codgrupo, $codrefeicao)
	{
		$SQL = 'SELECT * FROM "prato"."tbrel_grupo_refeicao" WHERE "FKcod_refeicao"='.$codrefeicao.' AND "FKcod_grupo"='.$codgrupo.';';
		
		$con = new gtiConexao();
		$con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);
		$con->gtiDesconecta();
		
		$desconto = '0';
		
		if ($tbl->RecordCount()>0)
		{
			foreach($tbl as $chave => $linha)
			{
				$desconto = trim($linha['desconto_gr']);
			}
		}
		
		return $desconto;
	}
	
	public function Excluir($codgrupo, $codrefeicao)
    {
    	$SQL = 'DELETE FROM "prato"."tbrel_grupo_refeicao" WHERE "FKcod_refeicao"='.$codrefeicao.' AND "FKcod_grupo"='.$codgrupo.';';

    	$con = new gtiConexao();
		$con->gtiConecta();
		$con->gtiExecutaSQL($SQL);
		$con->gtiDesconecta();
    }
    
    public function Alterar($codgrupo, $codrefeicao, $desconto)
    {
    	$SQL = 'UPDATE "prato"."tbrel_grupo_refeicao" SET "desconto_gr"='.$desconto.'  WHERE "FKcod_refeicao"='.$codrefeicao.' AND "FKcod_grupo"='.$codgrupo.';';
		
    	$con = new gtiConexao();
		$con->gtiConecta();
		$con->gtiExecutaSQL($SQL);
		$con->gtiDesconecta();
    }
    
    public function Salvar($codgrupo, $codrefeicao, $desconto)
    {
    $SQL = 'INSERT INTO "prato"."tbrel_grupo_refeicao" ("FKcod_refeicao","FKcod_grupo","desconto_gr") VALUES ('.$codrefeicao.','.$codgrupo.','.$desconto.');';
		
    	$con = new gtiConexao();
		$con->gtiConecta();
		$con->gtiExecutaSQL($SQL);
		$con->gtiDesconecta();
    }
	
	
}
?>