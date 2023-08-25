<?php

return '
<h3>Preencha o formulário para a inscrição</h3></br>
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
                        <div class="form-group col-md-8">
                            <label for="nomeCompleto">Nome completo<span class="text-danger font-weight-bold">*</span></label>
                            <input type="text" class="form-control" id="nomeCompleto" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="idCPF">CPF</label>
                            <input type="text" class="form-control" id="idCPF" value="' . $USER->username . '" readonly>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="dataNacimento">Data de nascimento<span
                                    class="text-danger font-weight-bold">*</span></label>
                            <input type="date" class="form-control" id="dataNacimento" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="nomeMae">Nome da Mãe<span class="text-danger font-weight-bold">*</span></label>
                            <input type="text" class="form-control" id="nomeMae" required>
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
    <!--Lotação----------------------------------------------------------------------->
    <div id="g_lotacao" class="card">
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
                <form id="formLotacao" method="post" enctype="multipart/form-data">


                    <div class="form-row">
                        <div class="form-group col-md-12">
                                <label for="lotacaoNucleo">Lotação<span
                                    class="text-danger font-weight-bold">*</span></label>
                                <select id="lotacaoNucleo" class="form-control" required>
                                    <option value="Chefia de Gabinete">Chefia de Gabinete</option>
                                    <option value="COMSET">Comunicação Setorial</option>
                                    <option value="Corregedoria-Geral">Corregedoria-Geral</option>
                                    <option value="CCMA">Gerência da Câmara de Conciliação, Mediação e Arbitragem</option>
                                    <option value="GDA">Gerência da Dívida Ativa</option>
                                    <option value="GSG">Gerência da Secretaria-Geral</option>
                                    <option value="GCP">Gerência de Cálculos e Precatórios</option>
                                    <option value="CEJUR">Gerência do Centro de Estudos Jurídicos</option>
                                    <option value="NA">Núcleo Administrativo</option>
                                    <option value="NCD">Núcleo Central de Distribuição</option>
                                    <option value="NCSI">Núcleo Central de Segurança e Inteligência</option>
                                    <option value="NNP">Núcleo de Negócios Públicos</option>
                                    <option value="NE">Núcleo Estratégico</option>
                                    <option value="PPMA">Procuradoria de Defesa do Patrimônio Público e do Meio Ambiente</option>
                                    <option value="Procuradoria do Estado na Capital Federal">Procuradoria do Estado na Capital Federal</option>
                                    <option value="PJ">Procuradoria Judicial</option>
                                    <option value="PROT">Procuradoria Trabalhista</option>
                                    <option value="PTR">Procuradoria Tributária</option>
                                    <option value="ProcuradoriasRegionais">Procuradorias Regionais</option>
                                    <option value="ProcuradoriasSetoriais">Procuradorias Setoriais</option>
                                    <option value="SuperintendênciaDeGestaoIntegrada">Superintendência de Gestão Integrada</option>
				                    <option value="Outros">Outros</option>
                                </select>
                        </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-12">
                              <label for="lotacaoCargo">Cargo<span
                                  class="text-danger font-weight-bold">*</span></label>
                              <select id="lotacaoCargo" class="form-control" required>
                                  <option value="ServidorAdministrativo">Servidor Administrativo</option>
                                  <option value="Assessorjuridico">Assessor Jurídico</option>
                                  <option value="EstagiarioGraduacao">Estagiário de Graduação</option>
                                  <option value="EstagiarioPosGraduacao">Estagiário de Pós-Graduação</option>
                              </select>
                      </div>
                    </div>


                    <div id="autoriSupHierarquicoDwl" class="form-row">
                        <div class="form-group col-md-12">
                            <a href="https://moodle.procuradoria.go.gov.br/moodle/public/AUTORIZACAO_SUPERIOR_HIERARQUICO.pdf" class="btn btn-outline-success btn-lg" role="button" target="_blank">Autorização do Superior Hierárquico - Modelo para download <i class="fa fa-download" aria-hidden="true"></i></a>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <div class="alert alert-warning" role="alert">
                                Os candidatos que integram o público interno (servidores e estagiários de graduação e pós-graduação da PGE-GO) deverão anexar autorização simples conferida pelo superior hierárquico para participação, conforme modelo constante do Anexo III. Tal exigência somente não será aplicável, excepcionalmente, aos estagiários de graduação oriundos do 13º Processo Seletivo e aos estagiários de pós-graduação do 3º Processo Seletivo.
                            </div>
                        </div>
                    </div>


                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="fileAutorizacaoSupHierar">Autorização do Superior Hierárquico assinada, 2MB</label><span class="text-danger font-weight-bold">*</span>
                            <input type="file" accept="application/pdf" class="form-control" id="fileAutorizacaoSupHierar" required><br>
                            <div id="downAutoririzacaoSupHierar"></div>
                        </div>
                    </div>


                    <div id="datasEstagio" class="form-row">
                        <div class="form-group col-md-6">
                           <label for="estagioDataInicio">Data início<span class="text-danger font-weight-bold">*</span></label>
                           <input type="date" class="form-control" id="estagioDataInicio" required>
                        </div>
                        <div class="form-group col-md-6">
                           <label for="estagioPrevisaoTermino">Previsão de término<span class="text-danger font-weight-bold">*</span></label>
                           <input type="date" class="form-control" id="estagioPrevisaoTermino" required>
                        </div>
                    </div>



                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <div id="msgAguardeLotacao"></div>
                            <input class="btn btn-primary" type="submit" id="btnCurso" value="Salvar"
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
