Relatórios e descrição do material processado e entregue.

## Trabalhos realizados

Como no presente contrato não ficou estabelecida entrega de PDF ou HTML publicáveis, todo o processo ficou registrado nos pŕoprios arquivos XML através de versionamento *git*.

Os [*commits*](https://github.com/ppKrauss/SBPqO-2021/commits/main) apresentam a sequência temporal de modificações, etapa a etapa,
viabilizando a sua **auditoria** e revisão colaborativa das entregas parciais. Destacam-se:

Etapa e seu commit | Resumo rescritivo
-------------------|--------------
**Originais recebidos** no<br/>[*commit* `03fe7e1`](https://github.com/ppKrauss/SBPqO-2021/tree/03fe7e185aba97b25379aa65d3b0f919c074134e/recebidoOriginal) | A [presernvação digital](https://en.wikipedia.org/wiki/Digital_preservation) e integridade são garantidas por este *commit* inicial e a planilha de metadados, [`originalFiles.csv`](../recebidoOriginal/originalFiles.csv).
Resultado da **Etapa 01a** no<br/>[*commit* `ec9f3b6`, na pasta `recebidoOriginal/Resumos`](https://github.com/ppKrauss/SBPqO-2021/tree/ec9f3b6b65a516045f8c9921d581a7ac89f1b91b/recebidoOriginal/Resumos) | Ver também  **_dump_ e relatório em [`etapa1a.md`](etapa1a.md)**.
Merge manual, de um resumo, no<br/>[*commit* `e27879e`](https://github.com/ppKrauss/SBPqO-2021/commit/e27879eb94a8fe8bee0a4b606c0ed2cbad00399b) | Ver [**seção final do relatório em `etapa1a.md`**](etapa1a.md#revisão-manual-da-etapa01a).
Resultado da **Etapa 01b** no<br/>[*commit* `cd124c0`](https://github.com/ppKrauss/SBPqO-2021/tree/cd124c05b4eb9a3fe85880ba0bccdf226a10614c/recebidoOriginal/Resumos) |  Ver também **_dump_ e relatório em [`etapa1b.md`](etapa1b.md)**.
Resultado da **Etapa 01c** no<br/>[*commit* `1c475f9`](https://github.com/ppKrauss/SBPqO-2021/tree/1c475f99900ac9a20921929c7a4883c3eec43e0f/recebidoOriginal/Resumos)| Ver também **_dump_ e relatório em [`etapa1c.md`](etapa1c.md)**.

Nos links para os respectivos *dumps* foram documentadas com mais detalhe as modificações realizadas.

-----

A seguir um resumo didático de cada etapa.

## Resumo da Etapa 1

Nesta etapa o foco é a "sanitização" dos arquivos XML, ou seja, os arquivos originais (pasta [recebidoOriginal](../../recebidoOriginal)), com XML inválido, **são convertidos para XML válido**, em [UTF-8](https://en.wikipedia.org/wiki/UTF-8) e com conteúdos em texto ([não `CDATA`](https://en.wikipedia.org/wiki/CDATA#Criticism)).

1. Conversão de *encoding* (**Etapa 01a**): foi necessária uma etapa de conversão padrão do *XML encoding* original "iso-8859-1" para "UTF-8". Tecnicamente a conversão fica mais simples se no mesmo processo os blocos `CDATA` forem expandidos para texto (e tratando eventuais tags HTML como texto).  Todo o processo pode ser reproduzido rodando-se o script `proc.php -x etapa1a`. Seus resultados e link para  _dump_ são apresentados na tabela acima. Tecnicamente são dois processos:

    1.1. Conversão "iso-8859-1" para "UTF-8", conforme padronizado por [libxml](http://www.xmlsoft.org/html/libxml-encoding.html) (usando [iconv](https://www.gnu.org/software/libiconv/)) no [DOMDocument PHP](https://www.php.net/manual/en/book.dom.php).

    1.2. Expansão dos blocos `CDATA` conforme [padrão SimpleXML do PHP](https://www.php.net/manual/en/book.simplexml.php) (invoca [parser NOCDATA libxml](http://www.xmlsoft.org/html/libxml-parser.html)) e seu uso no script.

2. **Correção manual de 01a**: eventuais falhas ou trabalhos artesanais (ex. merge de arquivos) são realizados neste momento, sem risco de adulterão no editor de texto (pois UTF8 padrão não fica adulterado pelo editor).

2. **Etapa 01b**: converte residuos XHTML do CDATA. Tags de formatação como `<sup>`, `<b>`, etc. podem ter sido indevidamente codificadas como CDATA, e, por conhecermos o comportamento original, podemos converter com segurança.

2. **Correção manual de 01b**: eventuais falhas ou trabalhos artesanais são realizados neste momento.

3. **Etapa 01c**: normalização de caracteres especiais.

4. **Correção manual e entrega final** dos arquivos XML com tags devidamente balanceadas e substituidas nas eventuais falhas de software e omições corretivas.

## Resumo da Etapa 2

Não foi solicitada em contrato, portanto nenhuma entrega foi realizada com relação a esta etapa.
