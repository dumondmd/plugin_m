<?php defined('BASEPATH') or exit('No direct script access allowed');

class Import_controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('admin/login_controller');
        }
        $this->load->model('Import_model', 'importM');
        $this->load->library('form_validation', 'csvimport');

    }

    public function index()
    {
        $header['titulo'] = "SRD Importação";
        $header['administrador'] = $this->session->userdata('nome');
        $this->load->view('admin/template/header', $header);
        $this->load->view('admin/import/index');
        $this->load->view('admin/template/footer');
    }

    function importcsv()
    {
        $add = 0;
        $upd = 0;
        $contador = 1;
        $data['errosimportacao'] = [];
        $contNaoAdd = 0;
        $update_data = null;
        $insert_data = null;
        $data['addressbook'] = $this->importM->getAll();
        $data['error'] = '';
        $config['encrypt_name'] = TRUE;
        $config['upload_path'] = './uploads/bens/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = '1000000';

        $this->load->library('upload', $config);


        // Upload

        try {
            $this->upload->do_upload();
            $file_data = $this->upload->data();
            $file_path = './uploads/bens/' . $file_data['file_name'];

            if ($this->csvimport->get_array($file_path)) {

                try {
                    $csv_array = $this->csvimport->get_array($file_path);
                } catch (\Exception $e) {
                    exit($e->getMessage());
                }
                $this->popTablesAdc($csv_array);

                foreach ($csv_array as $row) {
                    $contador++;


                    if ($row['tombamento'] && $row['unidade_administrativa'] && $row['descricao'] && $row['marca'] && $row['especie'] && $row['classe']) {


                        $idUa = $this->checkUA(removerAspas($row['unidade_administrativa']));
                        $idMarca = $this->checkMarca(removerAspas($row['marca']));
                        $idEspecie = $this->checkEspecie(removerAspas($row['especie']));
                        $idClasse = $this->checkClasse(removerAspas($row['classe']));
                        $EST_CON = $this->enumEstado($row['conservacao']);
                        $idBemPatrimonial = $this->checkTombamento($row['tombamento']);

                        //Atualizar bem material
                        if ($idBemPatrimonial) {
                            $update_data = [
                                'NUMR_TOMBAMENTO_ATUAL' => intval($row['tombamento']),
                                'NUMR_TOMBAMENTO_ANTERIOR' => intval($row['tomb_anterior']) ?? NULL,
                                'DESC_PATRIMONIO' => $row['descricao'],
                                'ID_UNIDADE_ADMINISTRATIVA' => intval($idUa),
                                'DESC_LOCALIZACAO' => $row['localizacao'],
                                'ID_MARCA' => intval($idMarca),
                                'STAT_CONSERVACAO' => $EST_CON,
                                'ID_ESPECIE' => intval($idEspecie),
                                'ID_CLASSE' => intval($idClasse),
                                'VALR_AQUISICAO' => intval(rmMeney($row['valor_aquisicao'])),
                                'VALR_ATUALIZADO' => intval(rmMeney($row['valor_atual'])),
                                'DATA_ATUALIZACAO' => date('Y-m-d H:i:s'),
                                'STAT_REGISTRO' => 1
                            ];


                            if ($this->importM->update($idBemPatrimonial, $update_data)) {
                                $upd++;
                            } else {
                                array_push($data['errosimportacao'], $this->verificarBensImportados($contador, $row));
                                $contNaoAdd++;
                            }


                            //Inserir Bem Patrimonial      
                        } else {
                            $insert_data = [
                                'NUMR_TOMBAMENTO_ATUAL' => intval($row['tombamento']),
                                'NUMR_TOMBAMENTO_ANTERIOR' => intval($row['tomb_anterior']) ?? NULL,
                                'DESC_PATRIMONIO' => $row['descricao'],
                                'ID_UNIDADE_ADMINISTRATIVA' => intval($idUa),
                                'DESC_LOCALIZACAO' => $row['localizacao'],
                                'ID_MARCA' => intval($idMarca),
                                'STAT_CONSERVACAO' => $EST_CON,
                                'ID_ESPECIE' => intval($idEspecie),
                                'ID_CLASSE' => intval($idClasse),
                                'VALR_AQUISICAO' => intval(rmMeney($row['valor_aquisicao'])),
                                'VALR_ATUALIZADO' => intval(rmMeney($row['valor_atual'])),
                                'STAT_REGISTRO' => 1
                            ];

                            if ($this->importM->add($insert_data)) {
                                $add++;
                            } else {
                                array_push($data['errosimportacao'], $this->verificarBensImportados($contador, $row));
                                $contNaoAdd++;
                            }
                        }

                    } else {
                        array_push($data['errosimportacao'], $this->verificarBensImportados($contador, $row));
                        $contNaoAdd++;
                    }


                }

                $this->session->set_flashdata('success', "CSV Data Importado com Sucesso: Inserções:  $add  Atualizações:  $upd");
                $this->session->set_flashdata('warning', "Linha(s):  $contNaoAdd  não adicionada(s)!");

                $header['titulo'] = "SRD Importação";
                $header['administrador'] = $this->session->userdata('nome');
                $this->load->view('admin/template/header', $header);
                $this->load->view('admin/import/index', $data);
                $this->load->view('admin/template/footer');
            } else {
                $data['error'] = "Erro no Upload, o arquivo CSV deve ser com ','";
            }

            //Deleta arquivo CSV temporário
            unlink($file_path);

            $header['titulo'] = "SRD Importação";
            $header['administrador'] = $this->session->userdata('nome');
            $this->load->view('admin/template/header', $header);
            $this->load->view('admin/import/index', $data);
            $this->load->view('admin/template/footer');

        } catch (\Throwable $th) {
            $this->session->set_flashdata('warning', "Importação não realizada, planilha em não conformidade com o padrão do sistema!<br>");

            //Deleta arquivo CSV temporário
            unlink($file_path);

            $header['titulo'] = "SRD Importação";
            $header['administrador'] = $this->session->userdata('nome');
            $this->load->view('admin/template/header', $header);
            $this->load->view('admin/import/index', $data);
            $this->load->view('admin/template/footer');
        }


    }


    function importBaixados()
    {
        $add = 0;
        $upd = 0;
        $contador = 1;
        $data['errosimportacao'] = [];
        $contNaoAdd = 0;
        $update_data = null;
        $insert_data = null;
        $data['addressbook'] = $this->importM->getAll();
        $data['error'] = '';
        $config['encrypt_name'] = TRUE;
        $config['upload_path'] = './uploads/bens-baixados/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = '1000000';

        $this->load->library('upload', $config);


        //Upload
        try {
            $this->upload->do_upload();
            $file_data = $this->upload->data();
            $file_path = './uploads/bens-baixados/' . $file_data['file_name'];

            if ($this->csvimport->get_array($file_path)) {

                try {
                    $csv_array = $this->csvimport->get_array($file_path);
                } catch (\Exception $e) {
                    exit($e->getMessage());
                }
                $this->popTablesBaixa($csv_array);

                foreach ($csv_array as $row) {
                    $contador++;


                    if ($row['Nº DE TOMBAMENTO'] && $row['UNIDADE'] && $row['DESCRIÇÃO'] && $row['VALOR AQUIS.'] && $row['DATA BAIXA']) {

                        $idUa = $this->checkUA(removerAspas($row['UNIDADE']));

                        $idBemPatrimonial = $this->checkTombamento($row['Nº DE TOMBAMENTO']);

                        $dataBaixaFormatada = $this->formatarDataBanco($row['DATA BAIXA']);

                        //Atulizar Bem Patrimonial
                        if ($idBemPatrimonial) {
                            $update_data = array(
                                'NUMR_TOMBAMENTO_ATUAL' => intval($row['Nº DE TOMBAMENTO']),
                                'NUMR_TOMBAMENTO_ANTERIOR' => intval($row['TOMBAMENTO ANTERIOR']) ?? NULL,
                                'DESC_PATRIMONIO' => $row['DESCRIÇÃO'],
                                'ID_UNIDADE_ADMINISTRATIVA' => intval($idUa),
                                'DESC_LOCALIZACAO' => $row['LOCALIZAÇÃO'],
                                'VALR_AQUISICAO' => intval(rmMeney($row['VALOR AQUIS.'])),
                                'VALR_ATUALIZADO' => intval(rmMeney($row['VALOR ATUAL'])),
                                'TIPO_BAIXA' => $row['TIPO BAIXA'],
                                'DATA_BAIXA' => $dataBaixaFormatada,
                                'STAT_REGISTRO' => 0,
                            );
                            if ($this->importM->update($idBemPatrimonial, $update_data)) {
                                $upd++;
                            } else {
                                array_push($data['errosimportacao'], $this->verificarBensImportados($contador, $row));
                                $contNaoAdd++;
                            }


                            //Inserir Bem Patrimonial    
                        } else {
                            $insert_data = array(
                                'NUMR_TOMBAMENTO_ATUAL' => intval($row['Nº DE TOMBAMENTO']),
                                'NUMR_TOMBAMENTO_ANTERIOR' => intval($row['TOMBAMENTO ANTERIOR']) ?? NULL,
                                'DESC_PATRIMONIO' => $row['DESCRIÇÃO'],
                                'ID_UNIDADE_ADMINISTRATIVA' => intval($idUa),
                                'DESC_LOCALIZACAO' => $row['LOCALIZAÇÃO'],
                                'VALR_AQUISICAO' => intval(rmMeney($row['VALOR AQUIS.'])),
                                'VALR_ATUALIZADO' => intval(rmMeney($row['VALOR ATUAL'])),
                                'TIPO_BAIXA' => $row['TIPO BAIXA'],
                                'DATA_BAIXA' => $dataBaixaFormatada,
                                'STAT_REGISTRO' => 0,
                            );
                            if ($this->importM->add($insert_data)) {
                                $add++;
                            } else {
                                array_push($data['errosimportacao'], $this->verificarBensImportados($contador, $row));
                                $contNaoAdd++;
                            }


                        }
                    } else {
                        array_push($data['errosimportacao'], $this->verificarBensImportados($contador, $row));
                        $contNaoAdd++;
                    }


                }

                $this->session->set_flashdata('success', "CSV Baixa com Sucesso: Inserções:  $add  Atualizações:  $upd");
                $this->session->set_flashdata('warning', "Linha(s):  $contNaoAdd  não adicionada(s)!");

                $header['titulo'] = "SRD Importação";
                $header['administrador'] = $this->session->userdata('nome');
                $this->load->view('admin/template/header', $header);
                $this->load->view('admin/import/index', $data);
                $this->load->view('admin/template/footer');
            } else {
                $data['error'] = "Erro no Upload, o arquivo CSV deve ser com ','";
            }

            //Deleta arquivo CSV temporário
            unlink($file_path);

            $header['titulo'] = "SRD Baixa";
            $header['administrador'] = $this->session->userdata('nome');
            $this->load->view('admin/template/header', $header);
            $this->load->view('admin/import/index', $data);
            $this->load->view('admin/template/footer');

        } catch (\Throwable $th) {
            $this->session->set_flashdata('warning', "Importação não realizada, planilha em não conformidade com o padrão do sistema!<br>");

            $header['titulo'] = "SRD Importação";
            $header['administrador'] = $this->session->userdata('nome');
            $this->load->view('admin/template/header', $header);
            $this->load->view('admin/import/index', $data);
            $this->load->view('admin/template/footer');
        }



    }

    /**
     * Função para popular colunas adicionais
     */
    public function popTablesAdc($table)
    {
        foreach ($table as $row) {

            if ($row['Nº DE TOMBAMENTO'] != null && $row['UNIDADE'] != null) {
                $idUa = $this->checkUA(removerAspas($row['UNIDADE']));
            }
        }
    }

    public function popTablesBaixa($table)
    {
        foreach ($table as $row) {

            if ($row['Nº DE TOMBAMENTO']) {
                $idUa = $this->checkUA(removerAspas($row['UNIDADE']));
            }
        }
    }

    /**
     * $STAT_CONSERVACAO = ['PÉSSIMO'=>'PESSIMO', 'RUIM'=>'RUIM', 'REGULAR'=>'REGULAR', 'BOM'=>'BOM', 'ÓTIMO'=>'OTIMO'];
     */

    public function enumEstado($estado)
    {
        if (!empty($estado)) {
            $STAT_CONSERVACAO = ['PÉSSIMO' => 'PESSIMO', 'RUIM' => 'RUIM', 'REGULAR' => 'REGULAR', 'BOM' => 'BOM', 'ÓTIMO' => 'OTIMO'];
            return $STAT_CONSERVACAO[$estado];
        } else {
            return false;
        }
    }
    /**
     * Função irá checar se existe a Unidade Administrativa
     */
    public function checkUA($ua)
    {

        if (!empty($res = $this->importM->getUA($ua))) {
            return $res;
        } else {
            $id = $this->importM->addUA($ua);
            return $id;
        }
    }
    /**
     * Função irá checar se existe a Marca
     */
    public function checkMarca($marca)
    {

        if (!empty($res = $this->importM->getMarca($marca))) {
            return $res;
        } else {
            $id = $this->importM->addMarca($marca);

            return $id;
        }
    }

    /**
     * Função irá checar se existe a Especie
     */
    public function checkEspecie($es)
    {

        if (!empty($res = $this->importM->getEspecie($es))) {
            ;
            return $res;
        } else {
            $id = $this->importM->addEspecie($es);
            return $id;
        }
    }

    /**
     * Função irá checar se existe a Classe
     */
    public function checkClasse($class)
    {

        if (!empty($res = $this->importM->getClasse($class))) {
            return $res;
        } else {
            $id = $this->importM->addClasse($class);

            return $id;
        }
    }

    /**
     * Função irá checar se existe o tombamento
     */
    public function checkTombamento($id)
    {
        return $this->importM->getTombamento(intval($id));
    }

    /**
     * Função irá checar bens baixados
     */
    public function verificarBensImportados($contador, $row)
    {

        $mensagem = '';
        $text = '';

        foreach ($row as $key => $value) {
            $mensagem .= '<p><span class="titulo">' . $key . '</span>: ' . $value . '</p>';
        }

        $text = '<p class="titulo">LINHA ' . $contador . '. Não foi adicionada por causa dos campos:</p><hr>';

        $text .= $mensagem;

        $text .= '<p>Não estarem de acordo com o formato CSV (,).</p>';

        return $text;
    }

    /**
     * Função converte a data do CSV para o formato que o banco de dados persista
     */
    public function formatarDataBanco($data)
    {
        $data = str_replace('/', '-', $data);
        $date = date_create($data);
        return date_format($date, 'Y-m-d H:i:s');
    }



}