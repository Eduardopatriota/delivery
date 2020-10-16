import React, { Component } from 'react';
import { Container, Icon, Right, Left, ListItem, Text, Toast } from 'native-base';
import { View } from 'react-native';
import { setProdSelc } from './../../redux/actions';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';

// import { Container } from './styles';

class Montagem extends Component {

  state = {
    sel: "true",
    qtd: 0,
    qtdSel: 0,
    select: []
  }

  selecionados = {

  }

  sec = [];

  constructor(props) {
    super(props);
    var obb = Object.entries(this.props.Iten.itens);

    for (var j = 0; j < obb.length; j++) {
      this.selecionar(obb[j][1].id, false)
    }
  }

  async componentDidMount() {
    var listTemp = this.props.preodSelc.produto;
    var list = this.props.preodSelc.produto[this.props.Iten.nome];
    list.splice(0, list.length)
    listTemp = { ...listTemp, [this.props.Iten.nome]: list }
    setProdSelc(listTemp);

    this.props.Iten.itens.map((i, ix) => (
      this.props.Iten.obriga === 'Sim' && ix === 0 ? this.marcarPrimeiro(i) : null
    ));
  }

  selecionar(title, filter) {
    if (this.selecionados[title] !== undefined) { // Testa se a chave existe
      this.state[title].push(filter);    // Adiciona um elemento no array
    } else {
      this.state[title] = filter;      // Se não existe, cria um array com um elemento
    }
  }


  render() {
    return (
      <View style={{ marginBottom: 20 }} >
        <Text style={{ fontSize: 15, fontWeight: "bold" }}>
          {this.props.Iten.nome} {this.props.Iten.obriga === 'Sim' ? '*' : null}
        </Text>

        {this.props.Iten.quantidade > 1 ?
          <Text style={{ fontSize: 11 }}>
            Selecione até {this.props.Iten.quantidade} opções
          </Text> :
          <Text style={{ fontSize: 11 }}>
            Selecione apenas 1 opção
          </Text>}
        {this.props.Iten.itens.map((i, ix) => (
          <ListItem style={{ marginLeft: 1, marginRight: 20, backgroundColor: this.state[i.id] ? '#e0e0e0' : '#fff'  }} onPress={async () => {

            if (this.state[i.id]) {
              console.log("Condição: ", "Obriga: " + this.props.Iten.obriga + " - Seq: " + this.sec.length)
              if (this.props.Iten.obriga === 'Sim' && this.sec.length === 1) { console.log("aqui") }
              else {
                const { setProdSelc } = this.props;
                var listTemp = this.props.preodSelc.produto;
                var listTemp2 = this.props.preodSelc.produto[this.props.Iten.nome];

                listTemp2.splice(i.nome, 1);

                this.sec = this.removerPorId2(this.sec, i.id);

                var pTemp = parseFloat(listTemp.preco) - parseFloat(i.preco)
                listTemp = { ...listTemp, preco: pTemp }
                setProdSelc(listTemp);

                listTemp = { ...listTemp, [this.props.Iten.nome]: listTemp2 }
                setProdSelc(listTemp);

                this.setState({ [i.id]: !this.state[i.id], qtd: this.state.qtd - 1, qtdSel: this.state.qtdSel - 1 })

                //this.getValorMenos();
              }
              console.log("State montagem", this.state)
            } else {
              if (this.state.qtd < this.props.Iten.quantidade) {

                const { setProdSelc } = this.props;
                var listTemp = this.props.preodSelc.produto;
                var listTemp2 = this.props.preodSelc.produto[this.props.Iten.nome];
                listTemp2.push(i.nome);
                var pTemp = parseFloat(listTemp.preco) + parseFloat(i.preco)
                listTemp = { ...listTemp, preco: pTemp }
                //setProdSelc(listTemp);

                this.sec.push(i);

                listTemp = { ...listTemp, [this.props.Iten.nome]: listTemp2 }
                //setProdSelc(listTemp);

                this.setState({ [i.id]: !this.state[i.id], qtd: this.state.qtd + 1, qtdSel: this.state.qtdSel + 1 });
                this.getValor();
              } else {
                if (this.props.Iten.obriga === 'Sim' && this.props.Iten.quantidade === "1") {
                  console.log("State dfsd")
                  this.props.Iten.itens.map((j, ix) => (
                    this.desmarcarAll(j)
                  ));
                  const delay = ms => new Promise(res => setTimeout(res, ms));

                  await delay(8);
                  this.marcar(i)
                } else if (this.props.Iten.obriga === 'Sim' && this.props.Iten.quantidade !== "1") {
                  const delay = ms => new Promise(res => setTimeout(res, ms));

                  //await delay(8);
                  //this.marcar(i)
                }




              }
              console.log("State montagem", this.state)
            }

          }}>
            <Left>
              <View>
                <Text style={{ fontSize: 13, width: '100%' }}>{i.nome} - R$ {this.mascaraValor(eval(parseFloat(i.preco)).toFixed(2))}</Text>
                <Text style={{ fontSize: 12, }}>{i.obs}</Text>
              </View>
            </Left>
            <Right style={{ marginRight: -15 }}>
              {this.state[i.id] ?
                <Icon name="checkcircleo" type="AntDesign" style={{ fontSize: 16, color: 'green' }} />
                : null}
            </Right>
            {}
          </ListItem>
        ))}

      </View>
    )
  }

  async marcarPrimeiro(i) {
    const delay = ms => new Promise(res => setTimeout(res, ms));

    await delay(8);
    this.marcar(i)
    // if (this.state[i.id]) { }
    // else {

    //   const { setProdSelc } = this.props;
    //   var listTemp = this.props.preodSelc.produto;
    //   var listTemp2 = this.props.preodSelc.produto[this.props.Iten.nome];
    //   listTemp2.push(i.nome);
    //   var pTemp = parseFloat(listTemp.preco)
    //   listTemp = { ...listTemp, preco: pTemp }
    //   //setProdSelc(listTemp);

    //   this.sec.push(i);

    //   listTemp = { ...listTemp, [this.props.Iten.nome]: listTemp2 }
    //   //setProdSelc(listTemp);

    //   this.setState({ [i.id]: !this.state[i.id], qtd: this.state.qtd + 1, qtdSel: this.state.qtdSel + 1 });
    //   //this.getValor();

    //   var PrecoTT = 0;
    //   var PrecoMM = 0;
    //   var PrecoMD = 0;
    //   var Qtd = 0;

    //   for (var i = 0; i < Object.entries(this.sec).length; i++) {
    //     PrecoTT = PrecoTT + parseFloat(this.sec[i].preco);
    //     Qtd = Qtd + 1;
    //     if (this.sec[i].preco > PrecoMM) {
    //       PrecoMM = this.sec[i].preco;
    //     }
    //     console.log('Valor: ', this.sec[i].preco)
    //   }

    //   PrecoMD = PrecoTT / Qtd;

    //   PrecoMD = (PrecoMD).toFixed(2);

    //   if (this.props.Iten.tipoPreco == 'Maior') {
    //     console.log('Maior')
    //     var pTemp = parseFloat(listTemp.preco) + parseFloat(PrecoMM);
    //     listTemp = { ...listTemp, preco: pTemp }
    //     setProdSelc(listTemp);
    //   } else if (this.props.Iten.tipoPreco == 'Divisão') {
    //     console.log('Divisão')
    //     var pTemp = parseFloat(listTemp.preco) + parseFloat(PrecoMD);
    //     listTemp = { ...listTemp, preco: pTemp }
    //     setProdSelc(listTemp);
    //   } else {
    //     console.log('Normal')        
    //     var pTemp = parseFloat(listTemp.preco) + parseFloat(PrecoTT);
    //     console.log('preco1', PrecoTT)
    //     console.log('preco2', listTemp.preco)
    //     console.log('resilt', pTemp)

    //     listTemp = { ...listTemp, preco: pTemp }

    //     setProdSelc(listTemp);
    //   }
    // }

  }

  desmarcarAll(i) {
    if (this.state[i.id]) {
      const { setProdSelc } = this.props;
      var listTemp = this.props.preodSelc.produto;
      var listTemp2 = this.props.preodSelc.produto[this.props.Iten.nome];

      listTemp2.splice(i.nome, 1);

      this.sec = this.removerPorId2(this.sec, i.id);

      var pTemp = parseFloat(listTemp.preco) - parseFloat(i.preco)
      listTemp = { ...listTemp, preco: pTemp }
      //setProdSelc(listTemp);

      listTemp = { ...listTemp, [this.props.Iten.nome]: listTemp2 }
      //setProdSelc(listTemp);

      this.setState({ [i.id]: false, qtd: this.state.qtd - 1, qtdSel: 0 })
      //this.getValor();

      var PrecoTT = 0;
      var PrecoMM = 0;
      var PrecoMD = 0;
      var Qtd = 0;

      for (var i = 0; i < Object.entries(this.sec).length; i++) {
        PrecoTT = PrecoTT + parseFloat(this.sec[i].preco);
        Qtd = Qtd + 1;
        if (this.sec[i].preco > PrecoMM) {
          PrecoMM = this.sec[i].preco;
        }
        console.log('Valor: ', this.sec[i].preco)
      }

      PrecoMD = PrecoTT / Qtd;

      PrecoMD = (PrecoMD).toFixed(2);

      //var listTemp = this.props.preodSelc.produto;

      if (this.props.Iten.tipoPreco == 'Maior') {
        console.log('Maior')
        var pTemp = parseFloat(listTemp.preco) - parseFloat(PrecoMM);
        listTemp = { ...listTemp, preco: pTemp }
        setProdSelc(listTemp);
      } else if (this.props.Iten.tipoPreco == 'Divisão') {
        console.log('Divisão')
        var pTemp = parseFloat(listTemp.preco) - parseFloat(PrecoMD);
        listTemp = { ...listTemp, preco: pTemp }
        setProdSelc(listTemp);
      } else {
        var pTemp = parseFloat(listTemp.preco) - parseFloat(PrecoTT);
        console.log('Normal, preco: ', pTemp)
        listTemp = { ...listTemp, preco: pTemp }
        setProdSelc(listTemp);
      }
    }

  }

  marcar(i) {
    if (this.state[i.id]) { }
    else {
      const { setProdSelc } = this.props;
      var listTemp = this.props.preodSelc.produto;
      var listTemp2 = this.props.preodSelc.produto[this.props.Iten.nome];
      listTemp2.push(i.nome);
      var pTemp = parseFloat(listTemp.preco) + parseFloat(i.preco)
      console.log("Qual preço? ", pTemp)
      listTemp = { ...listTemp, preco: pTemp }
      //setProdSelc(listTemp);

      this.sec.push(i);

      listTemp = { ...listTemp, [this.props.Iten.nome]: listTemp2 }
      //setProdSelc(listTemp);

      this.setState({ [i.id]: !this.state[i.id], qtd: this.state.qtd + 1, qtdSel: this.state.qtdSel + 1 });
      this.getValor();
    }
  }

  mascaraValor(valor) {
    valor = valor.toString().replace(/\D/g, "");
    valor = valor.toString().replace(/(\d)(\d{8})$/, "$1.$2");
    valor = valor.toString().replace(/(\d)(\d{5})$/, "$1.$2");
    valor = valor.toString().replace(/(\d)(\d{2})$/, "$1,$2");
    return valor
  }


  getValor() {
    var PrecoTT = 0;
    var PrecoMM = 0;
    var PrecoMD = 0;
    var Qtd = 0;

    for (var i = 0; i < Object.entries(this.sec).length; i++) {
      PrecoTT = PrecoTT + parseFloat(this.sec[i].preco);
      Qtd = Qtd + 1;
      if (this.sec[i].preco > PrecoMM) {
        PrecoMM = this.sec[i].preco;
      }
      console.log('Valor: ', this.sec[i].preco)
      console.log('Valor2: ', this.props.preodSelc.produto.preco)
    }

    PrecoMD = PrecoTT / Qtd;

    PrecoMD = (PrecoMD).toFixed(2);
    const { setProdSelc } = this.props;
    var listTemp = this.props.preodSelc.produto;

    if (this.props.Iten.tipoPreco == 'Maior') {
      console.log('Maior')
      var pTemp = parseFloat(listTemp.precoUn) + parseFloat(PrecoMM);
      listTemp = { ...listTemp, preco: pTemp }
      setProdSelc(listTemp);
    } else if (this.props.Iten.tipoPreco == 'Divisão') {
      console.log('Divisão')
      var pTemp = parseFloat(listTemp.precoUn) + parseFloat(PrecoMD);
      listTemp = { ...listTemp, preco: pTemp }
      setProdSelc(listTemp);
    } else {
      console.log('Normal')
      var pTemp = parseFloat(listTemp.precoUn) + parseFloat(PrecoTT);
      listTemp = { ...listTemp, preco: pTemp }
      setProdSelc(listTemp);
    }


  }

  getValorMenos() {
    var PrecoTT = 0;
    var PrecoMM = 0;
    var PrecoMD = 0;
    var Qtd = 0;

    for (var i = 0; i < Object.entries(this.sec).length; i++) {
      PrecoTT = PrecoTT + parseFloat(this.sec[i].preco);
      Qtd = Qtd + 1;
      if (this.sec[i].preco > PrecoMM) {
        PrecoMM = this.sec[i].preco;
      }
      console.log('Valor: ', this.sec[i].preco)
    }

    PrecoMD = PrecoTT / Qtd;

    PrecoMD = (PrecoMD).toFixed(2);
    const { setProdSelc } = this.props;
    var listTemp = this.props.preodSelc.produto;

    if (this.props.Iten.tipoPreco == 'Maior') {
      console.log('Maior')
      var pTemp = parseFloat(listTemp.preco) - parseFloat(PrecoMM);
      listTemp = { ...listTemp, preco: pTemp }
      setProdSelc(listTemp);
    } else if (this.props.Iten.tipoPreco == 'Divisão') {
      console.log('Divisão')
      var pTemp = parseFloat(listTemp.preco) - parseFloat(PrecoMD);
      listTemp = { ...listTemp, preco: pTemp }
      setProdSelc(listTemp);
    } else {
      console.log('Normal')
      var pTemp = parseFloat(listTemp.preco) - parseFloat(PrecoTT);
      listTemp = { ...listTemp, preco: pTemp }
      setProdSelc(listTemp);
    }


  }

  removerPorId2(array, id) {
    return array.filter(function (el) {
      return el.id !== id;
    });
  }

  QuantSel(array) {
    var e = 0;
    array.filter(function (el) {
      console('czxcz', el)
    });
  }
}

const mapStateToProps = state => ({
  preodSelc: state.SelecReducer
});

const mapDispatchToProps = dispatch =>
  bindActionCreators({ setProdSelc }, dispatch);

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(Montagem);
