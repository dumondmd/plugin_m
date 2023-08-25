<?php

return '
  <h3>Preencha os documentos para a inscrição</h3></br>
  <h6>Os campos com <span class="text-danger font-weight-bold">*</span> são de preenchimento obrigatório.</h6></br>
  <div class="accordion" id="accordionAluno">
      <!--Dados Pessoais-------------------------------------------------------------->
      <div id="g_dados_pessoais" class="card">
          <div class="card-header" id="headingOne">
              <h2 class="mb-0">
                  <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                      data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      <h4>Dados pessoais</h4>
                  </button>
              </h2>
          </div>
          <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionAluno">
              <div class="card-body">
                  <form id="formDadosPessoais" method="post" enctype="multipart/form-data">





                      <div class="form-row">
                          <input type="hidden" class="form-control" id="id" value="' . $USER->id . '" readonly>
                          <input type="hidden" class="form-control" id="idCurso" value="' . $_GET['id'] . '" readonly>
                          <div class="form-group col-md-8">
                              <label for="nomeCompleto">Nome completo<span class="text-danger font-weight-bold">*</span></label>
                              <input type="text" class="form-control" id="nomeCompleto" value="' . $usrReceita->nome . '" required>                              
                          </div>
                          <div class="form-group col-md-4">
                              <label for="cpf">CPF</label>
                              <input type="text" class="form-control" id="cpf" value="' . $USER->username . '" readonly>
                          </div>
                      </div>
                      <div class="form-row">
                          <div class="form-group col-md-4">
                              <label for="dataNacimento">Data de nascimento<span
                                      class="text-danger font-weight-bold">*</span></label>
                              <input type="date" class="form-control" id="dataNacimento" value="' . formatarData($usrReceita->dataNascimento) . '" required>
                          </div>
                          <div class="form-group col-md-4">
                              <label for="nomeMae">Nome da Mãe<span class="text-danger font-weight-bold">*</span></label>
                              <input type="text" class="form-control" id="nomeMae" value="' . $usrReceita->nomeMae . '" required>
                          </div>
                          <div class="form-group col-md-4">
                              <label for="nomePai">Nome do pai</label>
                              <input type="text" class="form-control" id="nomePai">
                          </div>
                      </div>
                      <div class="form-row">
                          <div class="form-group col-md-6">
                              <label for="rg">RG<span class="text-danger font-weight-bold">*</span></label>
                              <input type="text" class="form-control" id="rg" required>
                          </div>
                          <div class="form-group col-md-6">
                              <label for="orgaoExpedidor">Órgão expedidor<span
                                      class="text-danger font-weight-bold">*</span></label>
                              <input type="text" class="form-control" id="orgaoExpedidor" required>
                          </div>
                          
                          
                      </div>
                      <div class="form-row">
                          <div class="form-group col-md-12">
                              <label for="fileRGCPF">Documento oficial com foto e CPF (arquivo único), 2MB<span
                                      class="text-danger font-weight-bold">*</span></label>
                              <input type="file" class="form-control" accept="application/pdf" name="fileRGCPF"
                                  id="fileRGCPF" data-max-size="2000" required><br>
                              <div id="downDocOficial"></div>
                          </div>
                      </div>
                      <div class="form-row">
                          <div class="form-group col-md-12">
                              <div id="msgAguardeDadosPessoais"></div>
                              <input class="btn btn-primary" id="btnDadosPessoais" type="submit" value="Salvar"
                                  style="float:right;">
                          </div>
                      </div>
                  </form>
              </div>
          </div>
      </div>
      <!--Endereco-------------------------------------------------------------------->
      <div id="g_endereco" class="card">
          <div class="card-header" id="headingTwo">
              <h2 class="mb-0">
                  <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
                      data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                      <h4>Endereço</h4>
                  </button>
              </h2>
          </div>
          <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionAluno">
              <div class="card-body">
                  <form id="formEndereco" method="post" enctype="multipart/form-data">
                      <div class="form-row">
                          <div class="form-group col-md-3">
                              <label for="enderecoCep">CEP<span class="text-danger font-weight-bold">*</span></label>
                              <input type="text" class="form-control" id="enderecoCep" required>
                          </div>
                          <div class="form-group col-md-2">
                              <label for="enderecoNumero">Número</label>
                              <input type="text" class="form-control" id="enderecoNumero">
                          </div>
                          <div class="form-group col-md-2">
                              <label for="enderecoQuadra">Quadra</label>
                              <input type="text" class="form-control" id="enderecoQuadra">
                          </div>
                          <div class="form-group col-md-2">
                              <label for="enderecoLote">Lote</label>
                              <input type="text" class="form-control" id="enderecoLote">
                          </div>
                          <div class="form-group col-md-3">
                              <label for="enderecoCidade">Cidade<span
                                      class="text-danger font-weight-bold">*</span></label>
                              <input type="text" class="form-control" id="enderecoCidade" required>
                          </div>
                      </div>
                      <div class="form-row">
                          <div class="form-group col-md-2">
                              <label for="logadouro">Logadouro<span class="text-danger font-weight-bold">*</span></label>
                              <input type="text" class="form-control" id="logadouro" required>
                          </div>
                          <div class="form-group col-md-4">
                              <label for="enderecoBairro">Bairro<span
                                      class="text-danger font-weight-bold">*</span></label>
                              <input type="text" class="form-control" id="enderecoBairro" required>
                          </div>
                          <div class="form-group col-md-4">
                              <label for="enderecoComplemento">Complemento</label>
                              <input type="text" class="form-control" id="enderecoComplemento">
                          </div>
                          <div class="form-group col-md-2">
                              <label for="estadoUf">Estado (UF)<span class="text-danger font-weight-bold">*</span></label>
                              <select id="estadoUf" class="form-control" required>
                                  <option>GO</option>
                                  <option>AC</option>
                                  <option>AL</option>
                                  <option>AP</option>
                                  <option>AM</option>
                                  <option>BA</option>
                                  <option>CE</option>
                                  <option>DF</option>
                                  <option>ES</option>
                                  <option>MA</option>
                                  <option>MT</option>
                                  <option>MS</option>
                                  <option>MG</option>
                                  <option>PA</option>
                                  <option>PB</option>
                                  <option>PR</option>
                                  <option>PE</option>
                                  <option>PI</option>
                                  <option>RJ</option>
                                  <option>RN</option>
                                  <option>RS</option>
                                  <option>RO</option>
                                  <option>RR</option>
                                  <option>SC</option>
                                  <option>SP</option>
                                  <option>SE</option>
                                  <option>TO</option>
                              </select>
                          </div>
                      </div>
                      <div class="form-row">
                          <div class="form-group col-md-12">
                              <label for="fileComprovanteEndereco">Comprovante de endereço, 2MB<span
                                      class="text-danger font-weight-bold">*</span></label>
                              <input type="file" accept="application/pdf" class="form-control"
                                  id="fileComprovanteEndereco" required>
                          </div>
                      </div>
                      <div class="form-row">
                          <div class="form-group col-md-12">
                              <div id="msgAguardeEndereco"></div>
                              <input class="btn btn-primary" id="btnEndereco" type="submit" value="Salvar"
                                  style="float:right;">
                              <div id="downComprovanteEndereco"></div>
                          </div>
                      </div>
                  </form>
              </div>
          </div>
      </div>
      <!--Contato--------------------------------------------------------------------->
      <div id="g_contato" class="card">
          <div class="card-header" id="headingThree">
              <h2 class="mb-0">
                  <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
                      data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                      <h4>Contato</h4>
                  </button>
              </h2>
          </div>
          <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionAluno">
              <div class="card-body">
                  <form id="formContato" method="post" enctype="multipart/form-data">
                      <div class="form-row">
                          <div class="form-group col-md-6">
                              <label for="telefoneContato">Telefone de contato</label>
                              <input type="text" class="form-control" id="telefoneContato" maxlength="10">
                          </div>
                          <div class="form-group col-md-6">
                              <label for="whatsappNumero">Whatsapp<span
                                      class="text-danger font-weight-bold">*</span></label>
                              <input type="text" class="form-control" id="whatsappNumero" maxlength="10" required>
                          </div>
                      </div>
                      <div class="form-row">
                          <div class="form-group col-md-12">
                              <div id="msgAguardeContato"></div>
                              <input class="btn btn-primary" type="submit" id="btnContato" value="Salvar"
                                  style="float:right;">
                          </div>
                      </div>
                  </form>
              </div>
          </div>
      </div>
      <!--Curso----------------------------------------------------------------------->
      <div id="g_curso" class="card">
          <div class="card-header" id="headingFour">
              <h2 class="mb-0">
                  <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
                      data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                      <h4>Lotação</h4>
                  </button>
              </h2>
          </div>
          <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionAluno">
              <div class="card-body">
                  <form id="formCurso" method="post" enctype="multipart/form-data">


                      <div class="form-row">
                        <div class="form-group col-md-12">
                                <label for="graduacoLocalExercicio">Local para o qual pretende a vaga de estágio de pós-graduação, dentre as seguintes opções<span
                                    class="text-danger font-weight-bold">*</span></label>
                                <select id="graduacoLocalExercicio" class="form-control" required>
                                    <option value="goiania">Goiânia (Procuradoria-Geral do Estado)</option>
                                    <option value="brasilia">Brasília (Procuradoria do Estado na Capital Federal)</option>
                                    <option value="anapolis">Anápolis (Procuradoria Regional)</option>
                                </select>
                        </div>
                       </div>

                      <div class="form-row">
                          <div class="form-group col-md-12">
                              <div id="msgAguardeCurso"></div>
                              <input class="btn btn-primary" type="submit" id="btnCurso" value="Salvar"
                                  style="float:right;">
                          </div>
                      </div>


                  </form>
              </div>
          </div>
      </div>
      <!--Pis------------------------------------------------------------------------->
      <div id="g_pis" class="card">
          <div class="card-header" id="headingFive">
              <h2 class="mb-0">
                  <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
                      data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                      <h4>PIS</h4>
                  </button>
              </h2>
          </div>
          <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionAluno">
              <div class="card-body">
                  <form id="formPis" method="post" enctype="multipart/form-data">
                      <div class="form-row">
                          <div class="form-group col-md-12">
                              <label for="numeroPisQuestion">Selecione uma das opções, possui número do PIS ?</label>
                              <select id="numeroPisQuestion" class="form-control" required>
                                  <option value="n">Não</option>
                                  <option value="s">Sim</option>
                              </select>
                          </div>
                      </div>
                      <div class="form-row" id="descPis">
                          <div class="form-group col-md-6" id="numPISDesc">
                              <label for="numeroPis">Número do PIS<span
                                      class="text-danger font-weight-bold">*</span></label>
                              <input type="text" class="form-control" id="numeroPis" required>
                          </div>
                          <div class="form-group col-md-6">
                              <label for="fileDocPis">Documento PIS<span
                                      class="text-danger font-weight-bold">*</span></label>
                              <input type="file" accept="application/pdf" class="form-control" id="fileDocPis"
                                  required><br>
                              <div id="downDocPis"></div>
                          </div>
                      </div>



                      <div class="form-row">
                          <div class="form-group col-md-12">
                              <div id="msgPis"></div>
                              <input class="btn btn-primary" type="submit" id="btnPis" value="Salvar"
                                  style="float:right;">
                          </div>
                      </div>
                  </form>
              </div>
          </div>
      </div>
      <!--PCD------------------------------------------------------------------------->
      <div id="g_pcd" class="card">
          <div class="card-header" id="headingSix">
              <h2 class="mb-0">
                  <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
                      data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                      <h4>PCD - Pessoa com deficiência</h4>
                  </button>
              </h2>
          </div>
          <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordionAluno">
              <div class="card-body">
                  <form id="formPcd" method="post" enctype="multipart/form-data">
                      <div class="form-row">
                          <div class="form-group col-md-12">
                              <label for="pcdPossuiDeficiencia">Selecione uma das opções, possui alguma deficiência
                                  ?</label>
                              <select id="pcdPossuiDeficiencia" class="form-control">
                                  <option value="n">Não</option>
                                  <option value="s">Sim</option>
                              </select>
                          </div>
                      </div>
                      <div id="pcdDescID">
                          <div class="form-row">
                              <p>Eu, acima identificado(a), candidato(a) à função de estagiário(a) da Procuradoria-Geral
                                  do Estado de Goiás para provimento de vagas e formação de cadastro de reserva no XIII
                                  Programa de Estágio de Graduação, regido pelo Edital de Processo Seletivo Simplificado
                                  nº 02/2022-PGE-CEJUR, venho requerer vaga especial para PESSOA COM DEFICIÊNCIA. Nessa
                                  ocasião, apresento anexo LAUDO MÉDICO com a respectiva Classificação Estatística
                                  Internacional de Doenças e Problemas Relacionados à Saúde (CID).</p>
                          </div>
                          <div class="form-row">
                              <div class="form-group col-md-6">
                                  <label for="pcdData">Data do documento<span
                                          class="text-danger font-weight-bold">*</span></label>
                                  <input type="date" class="form-control" id="pcdData" required>
                              </div>
                              <div class="form-group col-md-6">
                                  <label for="fileAtestadoMedico">Atestado médico, 2MB<span
                                          class="text-danger font-weight-bold">*</span></label>
                                  <input type="file" accept="application/pdf" class="form-control" id="fileAtestadoMedico"
                                      required><br>
                                  <div id="downComprovanteAtestadoMedico"></div>
                              </div>
                          </div>
                          <div class="form-row">
                              <div class="form-group col-md-12">
                                  <label for="pcdTratamentoEspecial">Selecione uma das opções, na oportunidade, declaro
                                      que :</label>
                                  <select id="pcdTratamentoEspecial" class="form-control">
                                      <option value="s">Necessito de prova ou de tratamento especial</option>
                                      <option value="n">Não necessito de prova ou tratamento especial</option>
                                  </select>
                              </div>
                          </div>
                          <div class="form-row" id="pcdDescricaoDesc">
                              <div class="form-group col-md-12">
                                  <label for="pcdDescricao">Descreva, abaixo as condições especiais que necessita para a a
                                      realização de prova<span class="text-danger font-weight-bold">*</span></label>
                                  <textarea class="form-control" id="pcdDescricao" rows="3" required></textarea>
                              </div>
                          </div>
                      </div>
                      <div class="form-row">
                          <div class="form-group col-md-12">
                              <div id="msgAguadePcd"></div>
                              <input class="btn btn-primary" type="submit" id="btnPcd" value="Salvar"
                                  style="float:right;">
                          </div>
                      </div>
                  </form>
              </div>
          </div>
      </div>


  </div>
  </br>
  <div class="alert alert-warning" role="alert">
      Estou ciente de que a formalização da inscrição implica a aceitação de todas as regras e condições estabelecidas no
      edital do processo seletivo.
  </div>
  
';