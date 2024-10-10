<?php

function processarConfiguracao($configuracao)
{
  //faz a instância do banco de dados ser global para poder ser em qualquer parte da função
  global $DB;
  global $id;
  //aqui separamos a quebra de linha em um array, para isso que serve a função explode
  $linhas = explode("\n", trim($configuracao));

  //váriavel para se guardar a categoria atual do qual vai ser buscada os cursos no banco de dados
  $categoriaAtual = '';

  //foreach para processar cada uma das linhas
  foreach ($linhas as $linha) {
    //a função trim serve para se retirar os espaços do começo e final da linha
    $linha = trim($linha);

    //verificação para se definir se se trata de uma categoria ou de um filtro dos cursos que serão buscados
    if (strpos($linha, ':') !== false && trim(substr($linha, strpos($linha, ':') + 1)) === '') {
      $categoriaAtual = rtrim($linha, ':');
      $categoria = new stdClass();
      $categoria->name = $categoriaAtual;
      $id = $DB->insert_record('report_coursestats_categories', $categoria);
      
    } elseif (strpos($linha, ':') !== false) {
      //função list atribui a cada váriavel um valor do array passado
      list($codigo, $filtros) = explode(':', $linha);
      $codigo = trim($codigo);
      $filtros = trim($filtros);

      //verifica se está sendo pedido para adcionar todos os cursos
      if ($filtros == '*') {
        //busca no banco de dados todos os cursos
        $query = "SELECT fullname FROM {course} WHERE category = :codigo";
        $params = ['codigo' => $codigo];
        $cursos = $DB->get_records_sql($query, $params);
        foreach ($cursos as $curso) {
          $curso_add = new stdClass();
          $curso_add->name = $curso->fullname;
          $curso_add->coursestats_category_id = $id;
          $DB->insert_record('report_coursestats_courses', $curso_add);
        }
      }
      //verifica se existe mais de uma configuração de filtro para se buscar no banco de dados
      elseif (strpos($filtros, ',') !== false) {
        $cursos = explode(',', $filtros);
        foreach ($cursos as $curso) {
          $curso = trim($curso);

          //verifica se o filtro deseja pegar cursos que tenham algum nome especifico
          if (strpos($curso, '%') !== false) {
            //busca por cursos que contenham com algum nome especifico
            if (strpos($curso, '%') === 0 && strrpos($curso, '%') === strlen($curso) - 1) {
              $query = "SELECT fullname FROM {course} WHERE category = :codigo and shortname LIKE :curso";
              $params = ['codigo' => $codigo, 'curso' => $curso];
              $resultados = $DB->get_records_sql($query, $params);
              foreach ($resultados as $resultado) {
                $curso_add = new stdClass();
                $curso_add->name = $resultado->fullname;
                $curso_add->coursestats_category_id = $id;
                $DB->insert_record('report_coursestats_courses', $curso_add); 
              }
              //busca por cursos que terminam com algum nome especifico
            } elseif (strpos($curso, '%') === 0) {
              $query = "SELECT fullname FROM {course} WHERE category = :codigo and shortname LIKE :curso";
              $params = ['codigo' => $codigo, 'curso' => $curso];
              $resultados = $DB->get_records_sql($query, $params);
              foreach ($resultados as $resultado) {
                $curso_add = new stdClass();
                $curso_add->name = $resultado->fullname;
                $curso_add->coursestats_category_id = $id;
                $DB->insert_record('report_coursestats_courses', $curso_add);
              }
              //busca por cursos que começam com algum nome especifico
            } elseif (strrpos($curso, '%') === strlen($curso) - 1) {
              $query = "SELECT fullname FROM {course} WHERE category = :codigo and shortname LIKE :curso";
              $params = ['codigo' => $codigo, 'curso' => $curso];
              $resultados = $DB->get_records_sql($query, $params);

              foreach ($resultados as $resultado) {
                $curso_add = new stdClass();
                $curso_add->name = $resultado->fullname;
                $curso_add->coursestats_category_id = $id;
                $DB->insert_record('report_coursestats_courses', $curso_add);
              }
            }
          }
          //busca por um curso especifico
          else {
            $query = "SELECT fullname FROM {course} WHERE category = :codigo and shortname = :curso";
            $params = ['codigo' => $codigo, 'curso' => $curso];
            $resultado = $DB->get_record_sql($query, $params);
            $curso_add = new stdClass();
            $curso_add->name = $resultado->fullname;
            $curso_add->coursestats_category_id = $id;
            $DB->insert_record('report_coursestats_courses', $curso_add);
            
          }
        }
      }
      //faz as mesmas coisas de quando se tem mais de uma configuração no filtro mas sem precisar percorrer um vetor por ser apenas uma configuração de filtro
      else {
        if (strpos($filtros, '%') !== false) {
          $curso = $filtros;
          $curso = trim($curso);
          if (strpos($filtros, '%') === 0 && strrpos($filtros, '%') === strlen($filtros) - 1) {
            $query = "SELECT fullname FROM {course} WHERE category = :codigo and shortname LIKE :curso";
            $params = ['codigo' => $codigo, 'curso' => $curso];
            $resultado = $DB->get_records_sql($query, $params);
            $curso_add = new stdClass();
            $curso_add->name = $resultado->fullname;
            $curso_add->coursestats_category_id = $id;
            $DB->insert_record('report_coursestats_courses', $curso_add);
            
          } elseif (strpos($filtros, '%') === 0) {
            $query = "SELECT fullname FROM {course} WHERE category = :codigo and shortname LIKE :curso";
            $params = ['codigo' => $codigo, 'curso' => $curso];
            $resultado = $DB->get_records_sql($query, $params);
            $curso_add = new stdClass();
            $curso_add->name = $resultado->fullname;
            $curso_add->coursestats_category_id = $id;
            $DB->insert_record('report_coursestats_courses', $curso_add);

          } elseif (strrpos($filtros, '%') === strlen($filtros) - 1) {
            $query = "SELECT fullname FROM {course} WHERE category = :codigo and shortname LIKE :curso";
            $params = ['codigo' => $codigo, 'curso' => $curso];
            $resultado = $DB->get_records_sql($query, $params);
            $curso_add = new stdClass();
            $curso_add->name = $resultado->fullname;
            $curso_add->coursestats_category_id = $id;
            $DB->insert_record('report_coursestats_courses', $curso_add);
            
          }
        } else {
          $query = "SELECT fullname FROM {course} WHERE category = :codigo and shortname = :filtros";
          $params = ['codigo' => $codigo, 'filtros' => $filtros];
          $resultado = $DB->get_record_sql($query, $params);
          $curso_add = new stdClass();
          $curso_add->name = $resultado->fullname;
          $curso_add->coursestats_category_id = $id;
          $DB->insert_record('report_coursestats_courses', $curso_add);
          
        }
      }
    }
  }
}

?>