import React, { Component } from 'react';
import { View, Text, SafeAreaView, Image, TouchableOpacity, ScrollView, Platform } from 'react-native';
import { Icon, Item, Input, Button, Fab } from 'native-base';
var db = openDatabase({ name: 'icomprei.db', createFromLocation: 1 });
import { openDatabase } from 'react-native-sqlite-storage';
import api from './../../../utilites/api';
import { MAlert } from './../../../utilites/utilites';
import SkeletonPlaceholder from "react-native-skeleton-placeholder";
import ListCardapio from './../../fragments/listCardapio'
import { setCar, setCarEmp, setCurrentTab } from './../../../redux/actions/';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { SliderBox } from "react-native-image-slider-box";
import VariaveisGlobais from './../../../utilites/variaveisDoSistema';
// import { Container } from './styles';

const vglobais = new VariaveisGlobais();

class Home extends Component {

  state = {
    isLoading: false,
    colorHeader: Platform.OS === 'android' ? 'red' : "white",
    cardapio: [],
    cardapioOr: [],
    padrao: '1',
    images: [
      vglobais.getHost + '/img/Capa22.jpg'
      //require('./../../../../assets/banner.png'),
    ]
  }

  async componentDidMount() {
    this.setState({ isLoading: true });

    try {
      const response = await api.post('ws/ListGrupoProd.php');
      console.log('Testeee ', response.data)
      if (response.status === 200) {
        this.setState({
          cardapio: response.data,
          cardapioOr: response.data
        });
      } else {
        MAlert('Erro na comunicação com  servidor!', 'Atenção', false);
      }

    } catch (error) {
      MAlert('Erro na comunicação com servidor!', 'Atenção', false);
    }

    this.setState({ isLoading: false });


  }

  form() {
    return (
      <View>
        <ScrollView horizontal={true} showsHorizontalScrollIndicator={false} style={{ width: '100%' }}>
          <SliderBox images={this.state.images}
            autoplay
            circleLoop
            sliderBoxHeight={220}
            dotColor="red"
            inactiveDotColor="red"
            paginationBoxStyle={{
              position: "absolute",
              bottom: 0,
              padding: 0,
              alignItems: "center",
              alignSelf: "center",
              justifyContent: "center",
              paddingVertical: 10
            }}
            dotStyle={{
              width: 10,
              height: 10,
              borderRadius: 5,
              marginHorizontal: 0,
              padding: 0,
              margin: 0,
              backgroundColor: "rgba(128, 128, 128, 0.92)"
            }}
            ImageComponentStyle={{ borderRadius: 15, width: '97%', marginTop: 5 }}
            imageLoadingColor="#2196F3"
          />
        </ScrollView>

        <ScrollView showsVerticalScrollIndicator={false} style={{ width: '100%', marginLeft: 10, marginTop: 5, marginRight: 15 }}>

          <View>
            <Text style={{ fontSize: 23, fontWeight: "bold" }}>Cardápio</Text>
          </View>
          {
            this.state.padrao === '2' ? this.busc() : null
          }
          <View style={{ marginTop: 20, width: '95%' }}>
            {this.state.isLoading ? this.formS() : this.List()}
          </View>


        </ScrollView>


      </View>
    );
  }

  getValorCarrinho() {
    var vl = 0.00;
    var adc = 0.00

    this.props.carr.carrinho.map(i => (
      vl = eval(parseFloat(vl) + parseFloat(i.preco))
    ))
    vl = vl;

    //var temp = vl.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' });

    return this.mascaraValor(vl.toFixed(2))
  }

  mascaraValor(valor) {
    valor = valor.toString().replace(/\D/g, "");
    valor = valor.toString().replace(/(\d)(\d{8})$/, "$1.$2");
    valor = valor.toString().replace(/(\d)(\d{5})$/, "$1.$2");
    valor = valor.toString().replace(/(\d)(\d{2})$/, "$1,$2");
    return valor
  }

  List() {
    return (
      <View style={{ width: '100%', flexDirection: this.state.padrao === '1' ? 'row' : 'column', flexWrap: this.state.padrao === '1' ? 'wrap' : 'nowrap', justifyContent: 'space-between' }}>
        {this.state.cardapio.map(i => (
          <ListCardapio Imagem={i.imagem} Nome={i.nome} padrao={this.state.padrao} acao={() => {
            this.props.navigation.navigate('Cardapio',
              { id_grupo: i.id, nome_grupo: i.nome }
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
          <View style={{ width: '99%', height: 55 }} />
          <View style={{ width: '99%', height: 55, marginTop: 15 }} />
          <View style={{ width: '99%', height: 55, marginTop: 15 }} />
        </SkeletonPlaceholder>
      </View>

    );
  }

  header() {
    return (
      <View style={{ flexDirection: 'row', justifyContent: 'space-between', height: 60, paddingLeft: 5, paddingRight: 15, backgroundColor: this.state.colorHeader }}>
        <View style={{}}>
          <Text style={{ fontSize: 28, fontWeight: "bold", marginLeft: 15, marginTop: 7, color: Platform.OS === 'android' ? 'white' : "black" }}>Inicio</Text>
        </View>
        <TouchableOpacity style={{ marginTop: 16, flexDirection: 'row', flexDirection: 'row', justifyContent: 'space-between' }}
          onPress={() => {
            if (this.props.carr.logado) {
              const { setCurrentTab } = this.props;
              setCurrentTab(1)
            } else {
              MAlert('Neste momento precisamos que você se identifique, faça seu login ou cadastro!', 'Login necessário', false);
              this.props.navigation.navigate('Login');
            }
          }}
        >
          <Icon name="shoppingcart" type="AntDesign" style={{ color: Platform.OS === 'android' ? 'white' : "black", fontSize: 20, }} />
          <Text style={{ color: Platform.OS === 'android' ? 'white' : "black", marginLeft: 15, }}>R${this.getValorCarrinho()}</Text>
        </TouchableOpacity>
      </View>
    );
  }

  busc() {
    return (
      <Item style={{ marginLeft: 0, marginRight: 30, justifyContent: 'space-between', flexDirection: 'row', }} >
        <Input
          placeholder="Busca"
          value={this.state.consulta}
          returnKeyType='search'
          onChangeText={consulta => this.setState({ consulta })}
          onSubmitEditing={this.handlePresquisa}
        />
        <Button transparent light style={{ marginRight: 0, marginTop: 5, }} ref='wv1' onPress={this.handlePresquisa}>
          <Icon style={{ color: 'black' }} name="search" />
        </Button>
      </Item>
    )
  }

  handlePresquisa = async () => {
    var temp = this.state.cardapioOr;

    let result = temp.filter((item) => { return item.nome.match(this.state.consulta) });
    this.setState({ cardapio: result });
  }

  render() {
    return (
      <SafeAreaView>
        {/* Cabeçario */
          this.header()
        }

        {/* Corpo */}
        {this.form()}
      </SafeAreaView>
    );
  }
}

const mapStateToProps = state => ({
  carr: state.CarrinhoReducer
});

const mapDispatchToProps = dispatch =>
  bindActionCreators({ setCar, setCarEmp, setCurrentTab }, dispatch);

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(Home);
