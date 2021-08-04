(() => {
    Vue.component("simple-card", {
        props: ["noticia"],
        name: "simple-card",
        methods: {
            possuiPermissao() {
                const { autor_id } = this.noticia;
                return (
                    this.$root.usuarioEAdmin ||
                    this.$root.usuarioIdComparacao(autor_id)
                );
            },
            editarNoticia() {
                this.$root.editarNoticia(this.noticia);
            },
            apagarNoticia() {
                const { post_id } = this.noticia;
                this.$root.apagarNoticia(post_id);
            },
            verNoticiaCompleta(){
                this.$root.verNoticiaCompleta(this.noticia)
            }
        },
        computed: {
            dataFormatada() {
                const { created_at } = this.noticia;
                const data = new Date(Date(created_at));
                const dia = data.getDate().toString();
                const diaF = dia.length == 1 ? "0" + dia : dia;
                const mes = (data.getMonth() + 1).toString();
                const mesF = mes.length == 1 ? "0" + mes : mes;
                const anoF = data.getFullYear();
                return diaF + "/" + mesF + "/" + anoF;
            },
            conteudoParcial() {
                const { conteudo } = this.noticia;
                const novoConteudo = conteudo.padEnd(100).substring(0, 100);
                return novoConteudo + "...";
            },
            tituloParcial() {
                const { titulo } = this.noticia;
                return titulo.substring(0, 20) + "...";
            },
        },
        template: `
        <div class="col mt-2 px-2" v-if="noticia">
            <div class="card bg-light shadow col-12 p-md-1">
                <img :src="noticia.imagem" class="card-img-top" alt="..."
                    style="object-fit: cover; height: 130px;">
                <div class="card-body px-xl-2 px-lg-2 px-md-1 px-sm-0 px-0 py-3">
                    <h5 class="card-title">{{ tituloParcial }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">{{ dataFormatada }}</h6>

                    <p class="card-text">{{ conteudoParcial }}</p>
    
                    <div class="btn-toolbar justify-content-between">
                        <div class="btn-group-sm mr-2" role="group" aria-label="First group" v-if="possuiPermissao()">
                            <button type="button" class="btn btn-info" data-toggle="tooltip"
                                title="Editar" @click="editarNoticia()">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                
                            <button type="button" class="btn btn-danger" data-toggle="tooltip"
                            title="Excluir" @click="apagarNoticia()">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>

                        <div class="btn-group-sm mt-2 mt-sm-0 mt-md-0 mt-xl-0 mt-lg-0">
                            <button @click="verNoticiaCompleta" class="btn btn-outline-dark">
                                <i class="fas fa-eye"></i>
                                Leia mais
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `,
    });

    // ================================= [ PAINEL DINAMICO ]

    Vue.component("painel-dinamico", {
        name: "formulario-login",
        data() {
            return { estaAberto: false };
        },
        template: `
        <div class="position-fixed w-100 h-100" style="top: 0; z-index: 9999999;" v-if="estaAberto">
            <div class="col-lg-6 col-md-7 col-sm-12 h-100 shadow-lg float-right text-light bg-black p-5" style="overflow-y: auto;">
                <div class="p-4">
                    <!-- HEADER -->
                    <header class="clearfix">
                        <button @click="fecharPainel()" type="button" class="close text-light float-left"
                            aria-label="Fechar aba login">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </header>

                    <slot></slot>
                </div>
            </div>
            <div style="background-color: rgba(0, 0, 0, 0.7);" class="w-100 h-100" @click="fecharPainel()"></div>
        </div>
        `,
        methods: {
            fecharPainel() {
                this.estaAberto = false;
            },
            abrirPainel() {
                this.estaAberto = true;
            },
        },
    });

   /*  // =============================================== [ FORM LOGIN ]

    Vue.component("formulario-login", {
        props: ["noSucessoExecute"],
        name: "formulario-login",
        data() {
            return {
                abaAtual: "login",
                urlAutenticacao: "./source/controllers/usuarioController.php",
                method: "POST",
                form: {
                    nome: "",
                    email: "",
                    senha: "",
                    acao: "login",
                },
            };
        },
        methods: {
            enviar(event) {
                event.preventDefault();
                const form = this.$el.querySelector("form");
                const formData = new FormData(form);
                const { urlAutenticacao, method } = this;
                fetch(urlAutenticacao, { method, body: formData }).then(
                    async (response) => {
                        const message = response.json();
                        if (response.ok) {
                            const json = await response.json();
                            this.$root = json;
                        }
                        return message;
                    }
                );
            },
            ativarAbaLogin() {
                const acao = "login";
                this.form.acao = acao;
                this.abaAtual = acao;
            },
            ativarAbaRegistrar() {
                const acao = "cadastrar";
                this.form.acao = acao;
                this.abaAtual = acao;
            },
            painelLoginAbaAtualIgualA(aba) {
                return this.abaAtual === aba;
            },
            valideEmail: function (email) {
                var regex =
                    /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return regex.test(email);
            },
        },
        computed: {
            temCamposValidos() {
                const { nome, email, senha, acao } = this.form;
                if (!["login", "cadastrar"].includes(acao)) return false;
                if (acao === "cadastrar" && nome.length < 3) return false;
                if (senha.length < 3 || !this.valideEmail(email)) return false;

                return true;
            },
        },
        template: `
        <div>
            <div class="d-flex justify-content-center">
                <div class="btn-group" role="group" aria-label="Login ou cadastrar">
                    <button type="button" class="btn"
                        @click="ativarAbaLogin()"
                        :class="[{disabled: painelLoginAbaAtualIgualA('login')},painelLoginAbaAtualIgualA('login') ? 'btn-warning' : 'btn-outline-warning']">
                        Login
                    </button>

                    <button type="button" class="btn"
                    @click="ativarAbaRegistrar()"
                    :class="[{disabled: painelLoginAbaAtualIgualA('cadastrar')},painelLoginAbaAtualIgualA('cadastrar') ? 'btn-warning' : 'btn-outline-warning']">
                        Registrar
                    </button>
                </div>
            </div>

            <div id="painel-login" class="mt-5 bg-warning text-dark rounded">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <img class="img-fluid mt-5 mb-2" src="./assets/images/fake-new-logo.png" width="90"
                        height="90" alt="Logo">
                </div>

                <form class="w-auto mx-auto p-5">

                    <input name="acao" :value="abaAtual" hidden />

                    <div class="input-group mb-2" v-if="abaAtual == 'cadastrar'">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                        <input class="form-control" type="text" name="nome"
                            placeholder="Seu nome" required v-model="form.nome">
                    </div>

                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-at"></i>
                            </span>
                        </div>
                        <input class="form-control" type="email" name="email"
                            placeholder="Seu email" required v-model="form.email">
                    </div>

                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-key"></i>
                            </span>
                        </div>
                        <input class="form-control" type="password" name="senha"
                            placeholder="Sua senha" required v-model="form.senha">
                    </div>

                    <button :disabled="!temCamposValidos" @click="enviar" class="form-control font-weight-bolder btn btn-toolbar btn-outline-dark text-center justify-content-center">
                        {{ abaAtual }}
                    </button>
                </form>
            </div>
        </div>
        `,
    }); */

    // ======================== CRIAR NOTICIA

    Vue.component("formulario-noticia", {
        data() {
            return {
                titulo: "",
                conteudo: "",
                id: "",
                url: "./source/controllers/noticiaController.php",
                registro: true,
                tituloDoForm: 'Criar'
            };
        },
        methods: {
            enviar(event) {
                event.preventDefault();
                const url = "./source/controllers/noticiaController.php" + (this.registro ? '' : '/' + this.id);
                const form = this.$el.querySelector("form");
                const formData = new FormData(form);
                const { id } = 1; //this.noticia.autor_id;
                formData.append("autor_id", id);
                fetch(url , { method: "POST", body: formData }).then(response => this.$root.obterNoticias());
                this.$parent.fecharPainel();
            },
            atualizar(noticia) {
                this.registro = false;
                this.tituloDoForm = 'Editar'
                this.id = noticia.post_id;
                this.titulo = noticia.titulo;
                this.conteudo = noticia.conteudo;
            },
        },
        computed: {
            campoEstaoValidos() {
                return this.titulo.length > 1 && this.conteudo.length > 1;
            },
        },
        template: `
        <div class="row">
            <h3>{{ tituloDoForm }} noticia</h3>
            <form action="#" class="w-100">
                <div class="form-group">
                    <label for="titulo-input">
                        Titulo *
                    </label>
                    <input :value="titulo" class="form-control" type="text" name="titulo" id="titulo-input" required>
                </div>

                <div class="form-group">
                    <label for="conteudo-input">
                        Imagem
                    </label>
                    <input type="file" name="imagem" id="imagem-input">
                </div>

                <div class="form-group">
                    <label for="conteudo-input">
                        Conteudo *
                    </label>
                    <textarea class="form-control" name="conteudo" id="conteudo-input" cols="30"
                        rows="10" :value="conteudo" required></textarea>
                </div>

                <div class="form-group" :disabled="campoEstaoValidos">
                    <button class="btn btn-outline-dark" @click="enviar" type="button">Enviar</button>
                </div>
            </form>
        </div>
        `,
    });
})();
