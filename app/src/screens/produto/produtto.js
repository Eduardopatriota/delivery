import React, { Component } from 'react';
import { View, StatusBar, TouchableOpacity, SafeAreaView } from 'react-native';
import { Container, Footer, Tab, Tabs, TabHeading, Icon, Text, Content, FooterTab, Button, Textarea } from 'native-base';
import Image from 'react-native-image-progress';
import { Progress } from 'react-native-progress';
import VariaveisGlobais from './../../utilites/variaveisDoSistema';
import Carregando from './../fragments/carregando2'
import api from './../../utilites/api';
import { bindActionCreators } from 'redux';
import { setProdSelc, delProdSelc, setCar } from './../../redux/actions';
import { connect } from 'react-redux';
import Montagem from './../fragments/motagem'
import { MAlert } from './../../utilites/utilites'

// import { Container } from './styles';

class Produto extends Component {

  state = {
    adc: [],
    quant: 1,
    obs: '',
    isLoading: false,
  }

  async componentDidMount() {
    this.setState({ isLoading: true });
    const { setProdSelc, delProdSelc } = this.props;
    console.log('ProdSEnter: ', this.props.navigation.getParam('pp', ''))
    delProdSelc();

    const data = new FormData();
    data.append('produto', this.props.navigation.getParam('pp', '').id);

    try {
      const response = await api.post('ws/ListMontagem.php', data);
      console.log('resposta: ', response.data)
      if (response.status === 200) {
        if (response.status !== '[]') {
          this.setState({
            adc: response.data
          });
        }
        //console.log('sdf ss', this.state.adc[0].itens)
      } else {
        MAlert('Erro na comunicação com  servidor!', 'Atenção', false);
        
      }

    } catch (error) {
      console.log('sdf ss', error)
      MAlert('Erro na comunicação com servidor!!', 'Atenção', false);
    }
    var u =  this.props.navigation.getParam('pp', '');
    u = {... u, precoUn: u.preco};
    
    setProdSelc(u);
    this.setState({ isLoading: false });

  }

  mascaraValor(valor) {
    valor = valor.toString().replace(/\D/g, "");
    valor = valor.toString().replace(/(\d)(\d{8})$/, "$1.$2");
    valor = valor.toString().replace(/(\d)(\d{5})$/, "$1.$2");
    valor = valor.toString().replace(/(\d)(\d{2})$/, "$1,$2");
    return valor
  }

  form() {
    const vglobais = new VariaveisGlobais();
    return (
      <Container>
        <StatusBar
          backgroundColor="white"
          barStyle="dark-content"
        />
        <SafeAreaView>
          <Image
            source={{ uri: vglobais.getHostIMG + '/pod/' +  this.props.navigation.getParam('pp', '').imagem  }}
            indicator={Progress}
            indicatorProps={{
              size: 35,
              borderWidth: 0,
              color: 'rgba(150, 150, 150, 1)',
              unfilledColor: 'rgba(200, 200, 200, 0.2)'
            }}
            style={{
              marginTop: 0,
              width: '100%',
              height: 180,
              backgroundColor: '#d8dadc',
              position: 'relative',
              borderBottomRightRadius: 20,
              borderBottomLeftRadius: 20
            }}>
            <TouchableOpacity style={{ position: 'relative', marginLeft: 15, marginTop: 15, height: 48, width: 48, flexDirection: 'row', alignItems: 'center' }} onPress={() => {
              this.props.navigation.goBack()
            }}>
              <Icon type="Ionicons" name="ios-arrow-back" onPress={() => {
                this.props.navigation.goBack()
              }} />
            </TouchableOpacity>
          </Image>
          <View>
            <Text style={{ fontSize: 15, fontWeight: "bold", marginLeft: 15, marginTop: 15 }}>{this.props.navigation.getParam('pp', '').nome}</Text>
          </View>
          <View>
            <Text style={{ fontSize: 13, marginLeft: 15, marginTop: 5 }}>{this.props.navigation.getParam('pp', '').obs}</Text>
          </View>
          <View style={{ width: '98%' }}>
            <Text style={{ fontSize: 15, fontWeight: "bold", marginTop: 10, width: '97%', textAlign: 'right' }}>R$ {this.mascaraValor(eval(parseFloat(this.props.preodSelc.produto.preco)).toFixed(2))}</Text>
          </View>
        </SafeAreaView>
        <Content>
          <View style={{ marginLeft: 15, marginTop: 5, }}>
            {this.state.adc.map(i => (
              <Montagem Iten={i} />
            ))}
          </View>
          <View style={{ marginLeft: 15, marginTop: 5, width: '98%' }}>
            <Text style={{ fontSize: 15, fontWeight: "bold" }}>
              Quantidade
            </Text>
            <View style={{ flexDirection: 'row', alignItems: 'center', marginTop: 5, justifyContent: 'space-between', marginRight: 30 }}>
              <Button transparent onPress={this.minuss}>
                <Icon type='AntDesign' name='minus' style={{ color: 'black' }} />
              </Button>
              <Text style={{ fontSize: 20, fontWeight: "bold" }}>
                {this.state.quant}
              </Text>
              <Button transparent onPress={this.pluss}>
                <Icon type='AntDesign' name='plus' style={{ color: 'black' }} />
              </Button>
            </View>
          </View>
          <View style={{ marginLeft: 15, marginTop: 15, marginRight: 17 }}>
            <Textarea rowSpan={3} value={this.state.obs}  onChangeText={obs => this.setState({ obs })} bordered placeholder="Observação, Ex: (Sem salada, ou retirar cebola)" />
          </View>
          <Button full success style={{ marginLeft: 15, marginTop: 25, marginRight: 17 }} onPress={() => {
            this.setState({ isLoading: true });
            const { setCar, setProdSelc } = this.props;

            var listTemp = this.props.preodSelc.produto;
            var pTemp = parseFloat(this.props.preodSelc.produto.preco) * parseFloat(this.state.quant)
            listTemp = { ...listTemp, preco: pTemp, quant: this.state.quant, observacao: this.state.obs }
            //setProdSelc(listTemp);

            var tt = listTemp;
            setCar(tt);

            console.log('ProdSelc: ', this.props.carrinho);
            this.props.navigation.goBack()
          }}>
            <Text>Adcionar ao carrinho</Text>
          </Button>
          <View style={{ width: 10, height: 80 }} />
        </Content>
      </Container>
    );
  }

  pluss = () => {

    this.setState({
      quant: this.state.quant + 1
    });

  }

  minuss = () => {
    if (this.state.quant > 1) {
      this.setState({
        quant: this.state.quant - 1
      });
    }

  }

  render() {
    return (
      this.state.isLoading ? <Carregando /> : this.form()
    );
  }
}

const mapStateToProps = state => ({
  preodSelc: state.SelecReducer,
  carrinho: state.CarrinhoReducer
});

const mapDispatchToProps = dispatch =>
  bindActionCreators({ setProdSelc, delProdSelc, setCar }, dispatch);

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(Produto);
