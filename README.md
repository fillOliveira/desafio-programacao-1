# Amostra de Código de Programação
A idéia desta amostra de código é permitir que terceiros vejam meus códigos e avaliem melhor as minhas habilidades como desenvolvedor programador.



## Descrição do projeto
Há arquivos de texto com os dados de vendas da empresa. O projeto cria uma maneira para que estes dados sejam importados para um banco de dados.

O projeto é uma interface que aceita upload de arquivos, trata os dados os dados e os armazena num banco de dados relacional. (MySql)

1. Aceita o upload de arquivos separados por TAB com as seguintes colunas: purchaser name, item description, item price, purchase count, merchant address, merchant name. O Sistema assume que as colunas estarão sempre nesta ordem e que sempre haverá dados em cada coluna, e que sempre haverá uma linha de cabeçalho. Um arquivo de exemplo chamado example_input.tab no path "public/test/"(public/test/example_input.tab).

2. Interpreta o arquivo recebido, trata os dados, e salvaa corretamente a informação em um banco de dados relacional.

3. Exibe a receita bruta total representada pelo arquivo enviado após o upload.

4. Foi escrito usando PHP 7.1.0.

5. É um MVC criado do zero, organizado, padronizado, com PHP puro e simples de configurar e rodar, funciona em ambiente compatível com Unix (Linux ou Mac OS X). E foi focada ao máximo ser desenvolvido tudo do zero e sem plugins ou bibliotecas externas para demonstrar conhecimento de saber como é feito por de trás desses plugins e extenções prontas(Porém usei a extensão PDO).



## Como Configurar e Rodar a Aplicação
1. Certificar que o PHP o Apache estejam configurados de forma correta:
	a. mod_rewrite ativado.
	b. Extensão PDO do PHP ativado.
	c. Ter acesso à um banco de dados Mysql
		c.1. Ter uma "database" dedicada as tabelas do projeto
2. Configurar arquivos de conexão e constantes
	a. Subir/Importar as tabelas para o banco de dados, na database criada, arquivo SQL para criação de tabela estão na pasta "/migrations", no arquivo "migrations/table.sql"
	b. Configurar dados de conexão com o banco de dados no arquivo "lib/Config.php"
	c. configurar constante "APP_ROOT", que está na raiz do MVC, o arquivo "index.php", essa constante deve ter link para o projeto e ser um link absoluto porém SEM o "http://, https://,www., //". Exemplo: localhost/pasta_do_arquivo/



## Descrição das Páginas
Há 2 páginas.
1. Página principal com upload via droppable ou clicando no botão e a listagem de tokens pois o sistema conta com um sistema de API para upload, após o upload, mostra a margem bruta:
	OBS:
	a. Cria token e apartir deste token, permite subir o arquivo.
	b. Se o arquivo for inválido e o token já estiver sido criado, ele será reutilizado para o próximo arquivo.
2. Página de registro por token



## Autor
Felipe Oliveira <felipe.wget@gmail.com>
Data: 09/08/2018

## Observações
1. git clone https://github.com/myfreecomm/desafio-programacao-1.git
2. Alterei para pasta Nexaas