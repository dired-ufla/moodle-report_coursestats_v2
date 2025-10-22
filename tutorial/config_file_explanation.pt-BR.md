[Voltar](../README.pt-BR.md)

# Conhecendo a anatomia de um arquivo de configuração

Este tutorial apresenta a anatomia do arquivo de configuração do plugin "CourseStats". O conteúdo também está disponível em formato de [vídeo](https://www.youtube.com/watch?v=b8SyizBfEIs).

## 1. O que é um arquivo de configuração?
- O arquivo de configuração é um documento de texto que permite organizar e personalizar a categorização de cursos dentro do plugin. Ele é utilizado para definir categorias e cursos sem afetar a organização existente no Moodle. 

- Cada arquivo pode conter uma ou mais categorias, e cada categoria pode estar associada a uma lista de cursos.


## 2. Estrutura de um arquivo de configuração

- O arquivo é composto por categorias e suas respectivas listas de cursos.

    - Cada categoria no arquivo de configuração é identificada por um nome seguido pelo símbolo de dois pontos (`:`) no final. O nome da categoria é livre e pode ser definido pelo usuário.

    - Na linha abaixo de cada categoria, está a lista de cursos pertencentes a ela. 

- Cada entrada da lista de cursos contém:

    - O identificador da categoria no Moodle onde o curso se encontra, seguido pelo símbolo de dois pontos (`:`); e
    - O nome breve do(s) curso(s), separados por vírgula (`,`). O nome breve pode ser informado por completo ou apenas uma parte, como será explicado mais adiante.

## 3. Exemplo de um arquivo de configuração

- Considere o exemplo abaixo. Este arquivo é formado por duas categorias, **Matemática** e **Português**, e cada categoria contém dois cursos. Por exemplo, a **Matemática** é composta pelos cursos **1ef-mat** e **2ef-mat**, que advêm das categorias de ID `1` e `2` do Moodle.

```
Matemática:
1: 1ef-mat
2: 2ef-mat

Português:
1: 1ef-lp
2: 2ef-lp
```

- Já o próximo exemplo apresenta apenas uma categoria, **Primeiro Ano - EF**, que contém dois cursos, **1ef-mat** e **1ef-lp**, ambos pertencentes à categoria de ID `1` do Moodle. 

```
Primeiro Ano - EF:
1: 1ef-mat, 1ef-lp
```

## 4. Filtrando cursos de uma categoria

- O plugin permite simplificar a filtragem de cursos ao utilizar partes do nome breve do curso. Para isso, você pode usar o símbolo de porcentagem (`%`) para representar partes omitidas no nome.

    - Para filtrar todos os cursos de uma categoria que terminam com **mat**, use: `%mat`.
    - Para filtrar todos os cursos de uma categoria que começam com **mat**, use: `mat%`.
    - Para filtrar todos os cursos de uma categoria que contêm a palavra **mat**, use: `%mat%`.

- Além disso, para adicionar todos os cursos de uma determinada categoria do Moodle, você pode usar o símbolo asterisco (`*`).

- Considere o exemplo abaixo. Ele é formado por duas categorias: **Matemática** e **Geral**.

    - A categoria **Matemática** inclui todos os cursos da categoria de ID `1` no Moodle que terminam com **mat** (por exemplo, **1ef-mat**, **2ef-mat**).
    - A categoria **Geral** inclui todos os cursos da categoria de ID `1` no Moodle.

```
Matemática:
1: %mat

Geral:
1: *
```

## 5. Conclusão
- No [próximo tutorial](config_file_usage.pt-BR.md), será apresentado um exemplo prático de utilização do arquivo de configuração em uma instalação do Moodle, possibilitando a visualização dos resultados dessa configuração.
