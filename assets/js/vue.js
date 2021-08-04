(() => {
    new Vue({
        el: "#app",
        data() {
            return {
                user: { id: 1, nome: null, nivel_permissao: 8 },
                noticias: [],
                noticiaVista: {},
                endpoints: {
                    // usuario: "./source/controllers/usuarioController.php",
                    noticia: "./source/controllers/noticiaController.php",
                },
            };
        },
        created() {
            this.obterNoticias();
        },
        methods: {
            obterNoticias(pagina = 1) {
                const url = this.endpoints.noticia;
                const urlAbsoluto = `${url}?pagina=${pagina}`;
                fetch(urlAbsoluto, { method: "GET" })
                    .then((response) => {
                        if(response.ok) return response.json();
                        else return [];
                    })
                    .then((noticias) => {
                        this.noticias = noticias;
                    });
            },
            notifique(mensagem) {
                alert(mensagem);
            },
            usuarioIdComparacao(idUsuario) {
                const { id } = this.user;
                return true
                // return id == idUsuario;
            },
            apagarNoticia(id) {
                const escolha = confirm('Quer mesmo apagar essa noticia?')
                if(escolha) {
                    const url = this.endpoints.noticia;
                    fetch(`${url}/${id}`, { method: "DELETE" })
                        .then(async (response) => {
                            if(response.ok) this.obterNoticias(this.noticiaPathIdAtual);
                            else {
                                const mensagem = await response.text();
                                alert(mensagem);
                            }
                        })
                }
            },
            editarNoticia(noticia){
                const painel = this.$refs.painelCriarNoticia;
                painel.abrirPainel()
                setTimeout(()=> {
                    this.$refs.formNoticia.atualizar(noticia)
                }, 200)
            },
            verNoticiaCompleta(noticia){
                this.noticiaVista = noticia;
                this.$refs.painelLerNoticias.abrirPainel()
            }
        },
        computed: {
            // usuarioEAdmin() {
            //     const { nivel_permissao } = this.user;
            //     return nivel_permissao == 8;
            // },
            // usuarioPrimeiroNome() {
            //     const { nome } = this.user;
            //     return nome ? nome.split(" ")[0] : "";
            // },
            // usuarioEstaLogado() {
            //     const { nivel_permissao } = this.user
            //     return !nivel_permissao == 4;
            // },
            range(){
                const quantNoticias = this.noticias.length;
                const retorno = quantNoticias < 10 ? 1 : (quantNoticias/10) + 1
                return retorno;
            }
        }
    });
})();
