import React, { Component } from 'react';
import { View, StatusBar, TouchableOpacity } from 'react-native';
import { Container, Footer, Item, Input, TabHeading, Icon, Text, Content, FooterTab, Button, Fab } from 'native-base';
import { setProdSelc, delProdSelc, setCurrentTab } from './../../redux/actions';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import api from './../../utilites/api';
import SkeletonPlaceholder from "react-native-skeleton-placeholder";
import { MAlert } from './../../utilites/utilites';
import ListProdutos from './../fragments/listProdutos'

// import { Container } from './styles';

class Cardapio extends Component {
  state = {
    produtos: [],
    produtosOr: [],
    isLoading: false,
    colorHeader: Platform.OS === 'android' ? 'red' : "white",
    consulta: ''
  }

  async componentDidMount() {
    this.setState({ isLoading: true });
    const data = new FormData();
    data.append('id_grupo', this.props.navigation.getParam('id_grupo', ''));

    try {
      const response = await api.post('ws/ListProd.php', data);
      console.log('respostaPR: ', response.data)
      if (response.status === 200) {
        this.setState({
          produtos: response.data,
          produtosOr: response.data,
        });
      } else {
        MAlert('Erro na comunicação com  servidor!', 'Atenção', false);
      }

    } catch (error) {
      MAlert('Erro na comunicação com servidor!', 'Atenção', false);
    }

    this.setState({ isLoading: false });
  }

  listProd() {
    const { delProdSelc } = this.props;
    return (
      <View>
        {this.state.produtos.map(i => (
          <ListProdutos Imagem={i.imagem} Nome={i.nome} Obs={i.obs} Preco={i.preco} acao={() => {
            delProdSelc();
            this.props.navigation.navigate('Produto',
              { pp: i }
            );
          }} />
        ))}
      </View>
    )
  }

  formS() {
    return (
      <View>
        <SkeletonPlaceholder>
          <View style={{ width: '94%', height: 75 }} />
          <View style={{ width: '94%', height: 75, marginTop: 15 }} />
          <View style={{ width: '94%', height: 75, marginTop: 15 }} />
        </SkeletonPlaceholder>
      </View>
    )
  }

  getValorCarrinho() {
    var vl = 0.00;
    var adc = 0.00

    this.props.carr.carrinho.map(i => (
      vl = eval(parseFloat(vl) + parseFloat(i.preco))
    ))
    vl = vl;


    return this.mascaraValor(vl.toFixed(2))
  }

  mascaraValor(valor) {
    valor = valor.toString().replace(/\D/g, "");
    valor = valor.toString().replace(/(\d)(\d{8})$/, "$1.$2");
    valor = valor.toString().replace(/(\d)(\d{5})$/, "$1.$2");
    valor = valor.toString().replace(/(\d)(\d{2})$/, "$1,$2");
    return valor
  }

  header() {
    return (
      <View style={{ flexDirection: 'row', justifyContent: 'space-between', backgroundColor: this.state.colorHeader, height: 60 }}>
        <View style={{ marginLeft: 15, marginTop: 5, flexDirection: 'row' }}>
          <TouchableOpacity style={{ height: 48, width: 30, flexDirection: 'row', alignItems: 'center' }} onPress={() => {
            this.props.navigation.goBack()
          }}>
            <Icon type="AntDesign" name="arrowleft" style={{ color: Platform.OS === 'android' ? 'white' : "black" }} />
          </TouchableOpacity>
          <View style={{ height: 48, flexDirection: 'row', alignItems: 'center', marginTop: -2, marginLeft: 10 }}>
            <Text style={{ fontSize: 28, fontWeight: "bold", color: Platform.OS === 'android' ? 'white' : "black" }}>Cardápio</Text>
          </View>
        </View>
        <TouchableOpacity style={{ marginTop: 16, flexDirection: 'row', justifyContent: 'space-between', marginRight: 20 }}
          onPress={() => {
            if (this.props.carr.logado) {
              const { setCurrentTab } = this.props;
              setCurrentTab(1);
              this.props.navigation.goBack()
            } else {
              MAlert('Neste momento precisamos que você se identifique, faça seu login ou cadastro!', 'Login necessário', false);
              this.props.navigation.navigate('Login');
            }
          }}
        >
          <Icon name="shoppingcart" type="AntDesign" style={{ color: Platform.OS === 'android' ? 'white' : "black", fontSize: 20, }} />
          <Text style={{ marginLeft: 15, color: Platform.OS === 'android' ? 'white' : "black" }}>R${this.getValorCarrinho()}</Text>
        </TouchableOpacity>
      </View>
    );
  }

  pesquisaP() {
    var temp = this.state.produtosOr;

    let result = temp.filter((item) => { return item.nome.match(this.state.consulta) });
    this.setState({ produtos: result });
  }

  handlePresquisa = async () => {
    var temp = this.state.produtosOr;

    let result = temp.filter((item) => { return item.nome.toLowerCase().match(this.state.consulta.toLowerCase()) });
    this.setState({ produtos: result });
  }

  render() {
    return (
      <Container>
        <Content>
          {this.header()}
          <Item style={{ marginLeft: 15, marginRight: 20, justifyContent: 'space-between', flexDirection: 'row', }} >
            <Input
              placeholder="Busca"
              value={this.state.consulta}
              returnKeyType='search'
              onKeyPress={this.handlePresquisa}
              onChangeText={consulta => this.setState({ consulta })}
              onSubmitEditing={this.handlePresquisa}
            />
            <Button transparent light style={{ marginRight: 0, marginTop: 5, }} ref='wv1' onPress={this.handlePresquisa}>
              <Icon style={{ color: 'black' }} name="search" />
            </Button>
          </Item>
          <View>
            <Text style={{ fontSize: 18, fontWeight: "bold", marginLeft: 15, marginTop: 15 }}>{this.props.navigation.getParam('nome_grupo', '')}</Text>
          </View>
          <View style={{ marginLeft: 15, marginTop: 5 }}>
            {this.state.isLoading ? this.formS() : this.listProd()}
          </View>
        </Content>
        {/* <Fab
          direction="up"
          containerStyle={{}}
          style={{ backgroundColor: 'red', marginBottom: 50 }}
          position="bottomRight"
          onPress={() => Linking.openURL('https://api.whatsapp.com/send?phone=5561983111888&data=AbvJe2XDlwgR_gYeZ6tt2N-Mh5uAwwMnX8TWOpYC3JDNvvFBjsRZESxXui6at63zzkcD2LkGU21SUWfqeEAD_Owv6YoJwui0vnA3HDoO4GyFo-7C-nyJR4TcjhiUwbRBCVE&source=FB_Ads&fbclid=IwAR3Iho4uch2202Ori14BDnOqmiftVzcUPCJ6zRd2cO5oXaqBARzLnoh0k5Q')}>
          <Icon name="logo-whatsapp" />
        </Fab> */}
        {Object.entries(this.props.carr.carrinho).length > 0 ?
          <Footer style={{ backgroundColor: 'green' }}>
            <TouchableOpacity style={{ width: '100%', marginLeft: 30, flexDirection: 'row', justifyContent: 'space-between' }} onPress={
              () => {
                if (this.props.carr.logado) {
                  const { setCurrentTab } = this.props;
                  setCurrentTab(1);
                  this.props.navigation.goBack()
                } else {
                  MAlert('Neste momento precisamos que você se identifique, faça seu login ou cadastro!', 'Login necessário', false);
                  this.props.navigation.navigate('Login');
                }
              }
            }>
              <Text style={{ color: '#fff', marginTop: 15, fontSize: 12 }}>Valor parcial: R$ {this.getValorCarrinho()}</Text>
              <Button iconLeft transparent light style={{ marginRight: 20, }} onPress={
                () => {
                  const { setCurrentTab } = this.props;
                  setCurrentTab(1);
                  this.props.navigation.goBack();
                }
              }>
                <Icon type='MaterialCommunityIcons' name='cart' style={{ color: 'white', fontSize: 12 }} />
                <Text style={{ color: 'white', fontSize: 12 }}>Finalizar Pedido</Text>
              </Button>
            </TouchableOpacity>
          </Footer> : null
        }
      </Container>
    );
  }
}

const mapStateToProps = state => ({
  preodSelc: state.SelecReducer,
  carr: state.CarrinhoReducer
});

const mapDispatchToProps = dispatch =>
  bindActionCreators({ setProdSelc, delProdSelc, setCurrentTab }, dispatch);

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(Cardapio);
