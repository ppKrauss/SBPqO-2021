## Dump da Etapa 01a - Conversão inicial

Durante o processamento formam detectados os seguintes intervalos de ID:
* AO.xml: AO0001 .. AO0220
* COL.xml: COL001 .. COL013
* DMG.xml: DMG001 .. DMG007
* FC.xml: FC001 .. FC029
* HA.xml: HA001 .. HA020
* LHC.xml: LHC001 .. LHC012
* LHI.xml: LHI001 .. LHI014
* PDI.xml: PDI001 .. PDI006
* PE.xml: PE001 .. PE047
* PI.xml: PI0001 .. PI0611
* PN0468.xml: PN0468 .. PN0468
* PN.xml: PN0001 .. PN1492
* PO.xml: PO001 .. PO029
* RCR.xml: RCR001 .. RCR360
* RS.xml: RS001 .. RS248

Resultados para conferir inconsitências:

* **Node names**: Resumo; Sigla; Titulo; Autores; Universidade; Conflito; Conclusao; Apoio.

* **Node paths**: /Resumos/Resumo[]; /Resumos/Resumo[]/Sigla; /Resumos/Resumo[]/Titulo; /Resumos/Resumo[]/Autores; /Resumos/Resumo[]/Universidade; /Resumos/Resumo[]/Conflito; /Resumos/Resumo[]/Resumo; /Resumos/Resumo[]/Conclusao; /Resumos/Resumo[]/Apoio; /Resumos/Resumo; /Resumos/Resumo/Sigla; /Resumos/Resumo/Titulo; /Resumos/Resumo/Autores; /Resumos/Resumo/Universidade; /Resumos/Resumo/Conflito; /Resumos/Resumo/Resumo; /Resumos/Resumo/Conclusao; /Resumos/Resumo/Apoio.

* **Contagens** de *node paths*: "/Resumos/Resumo[]":2981,"/Resumos/Resumo[]/Sigla":2981,"/Resumos/Resumo[]/Titulo":2981,"/Resumos/Resumo[]/Autores":2981,"/Resumos/Resumo[]/Universidade":2981,"/Resumos/Resumo[]/Conflito":2981,"/Resumos/Resumo[]/Resumo":2981,"/Resumos/Resumo[]/Conclusao":2981,"/Resumos/Resumo[]/Apoio":2981,"/Resumos/Resumo":1,"/Resumos/Resumo/Sigla":1,"/Resumos/Resumo/Titulo":1,"/Resumos/Resumo/Autores":1,"/Resumos/Resumo/Universidade":1,"/Resumos/Resumo/Conflito":1,"/Resumos/Resumo/Resumo":1,"/Resumos/Resumo/Conclusao":1,"/Resumos/Resumo/Apoio":1.

A exceção com contagem unitária se deve ao arquivo `PN0468`.  
Os resultados ficaram registrados na forma de [*commit* ec9f3b6](https://github.com/ppKrauss/SBPqO-2021/commit/ec9f3b6b65a516045f8c9921d581a7ac89f1b91b): média de 90% do código-fonte revisado, exceto por PN.xml com ~70%.
```
 15 files changed, 29896 insertions(+), 29896 deletions(-)
 rewrite recebidoOriginal/Resumos/AO.xml (88%)
 rewrite recebidoOriginal/Resumos/COL.xml (94%)
 rewrite recebidoOriginal/Resumos/DMG.xml (90%)
 rewrite recebidoOriginal/Resumos/FC.xml (90%)
 rewrite recebidoOriginal/Resumos/HA.xml (93%)
 rewrite recebidoOriginal/Resumos/LHC.xml (93%)
 rewrite recebidoOriginal/Resumos/LHI.xml (91%)
 rewrite recebidoOriginal/Resumos/PDI.xml (92%)
 rewrite recebidoOriginal/Resumos/PE.xml (91%)
 rewrite recebidoOriginal/Resumos/PI.xml (80%)
 rewrite recebidoOriginal/Resumos/PN.xml (68%)
 rewrite recebidoOriginal/Resumos/PN0468.xml (90%)
 rewrite recebidoOriginal/Resumos/PO.xml (91%)
 rewrite recebidoOriginal/Resumos/RCR.xml (85%)
 rewrite recebidoOriginal/Resumos/RS.xml (86%)
```

## Revisão manual da etapa01a

Nenhuma correção foi necessária, apenas o merge de `PN0468.xml`  com `PN.xml` para manter a padronização.
