# SisVendas

Este projeto diz respeito ao desenvolvimento de uma plataforma para vendas online, proposto como desafio na disciplina de Desenvolvimento de Aplicações para Dispositivos Móveis. Atualmente se encontra em versão inicial, 0.1.

# 1 - Requisitos obrigatórios para execução:

-> PHP 7.0 ou superior;
-> MySQL Ver 14.14 ou superior recomendada;
-> Servidor Web, recomendado Apache HTTP Server em sua versão mais recente;


# 2 - Requisitos Opcionais para execução:

-> MySQL Workbench;


# 3 - Como executar a aplicação?

  3.1 - Criar base de dados entitulada "Comercial", junto ao MySQL;
  
  3.2 - Executar o script "comercial.sql" junto a base de dados criada (Localizado no diretório SisVendas/BD) ;
        
  3.3 - Caso deseje visualizar o modelo lógico do BD, acessar o arquivo "MER_Comercial.mwb" junto ao MySQL Workbench (Localizado no diretório SisVendas/BD);
  
  3.5 - Editar a classe "bancodedadospdo.class.php", presente no diretório SisVendas/BD, alterando usuário e senha do BD presentes no construtor, com os novos dados de acesso;
  
  3.6 - Acessar via webserver, o diretório SisVendas/Modulos;
  
  3.7 - Acessar o arquivo "login.php".
  
  
  
 # 4 - Como cadastrar clientes?
  
  -> Acessar "SisVendas/Modulos/cadastracliente.php".
    
  
 # 5 - Como cadastrar fornecedores?
  
  -> Acessar "SisVendas/Modulos/cadastrafornecedor.php".
    
    
 # 6 - Como cadastrar produtos?
 
  -> Acessar "SisVendas/Modulos/cadastraproduto.php".
    
    
 # Agradecimentos
 
   FSW Acadêmica do IFG-Câmpus Inhumas e seus colaboradores, pelo desenvolvimento das classes de conexão a base de dados.
