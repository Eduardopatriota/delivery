import React, { Component } from 'react';
import { Container, Footer, Tab, Tabs, Fab, Icon, Text, Content, FooterTab, Button, Badge } from 'native-base';
import { View, StatusBar, ScrollView, Linking } from 'react-native';
import styles from './styles';
import { openDatabase } from 'react-native-sqlite-storage';
var db = openDatabase({ name: 'delivery.db', createFromLocation: 1 });
import api from './../../utilites/api';
import Home from './home/home'
import Carrinho from './carrinho/carrinho'
import Usuario from './usuario/usuario'
import { MAlert } from './../../utilites/utilites';
import { setCar, getCar, setCarEmp, setCurrentTab, setPedidoA } from './../../redux/actions';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

class Main extends Component {
  state = {
    idUser: '',
    endPadrao: '',
    auth_token: '',
    logado: false
  }

  async componentDidMount() {

  }

  async getServidor() {
    try {
      const data = new FormData();
      data.append('token', this.state.auth_token);
      data.append('user_id', this.state.idUser);

      // const response = await api.post('public/api/get-user-notifications', data);
      // console.log("resposta ", response.data)
      // if (response.status === 200) {
      //   this.setState({
      //     not: [...response.data],
      //   });

      //   this.state.not.map(i => (
      //     this.setState({ numNotificacao: this.state.numNotificacao + (i.is_read === 0 ? 1 : 0) })
      //   ));

      //   console.log('Notificacões: ', this.state.numNotificacao);
      // }

    } catch (error) {
      console.log('Erro: ', error)
    }
  }

  render() {

    return (

      <Container>
        <StatusBar
          backgroundColor={Platform.OS === 'android' ? "red" : "white"}
          barStyle={Platform.OS === 'android' ? "ligth-content" : "dark-content"}
        />
        <Content>
          {this.renderJanelas()}
        </Content>
        {/* <Fab
          direction="up"
          containerStyle={{}}
          style={{ backgroundColor: 'red', marginBottom: 70 }}
          position="bottomRight"
          onPress={() => Linking.openURL('https://api.whatsapp.com/send?phone=5561983111888&data=AbvJe2XDlwgR_gYeZ6tt2N-Mh5uAwwMnX8TWOpYC3JDNvvFBjsRZESxXui6at63zzkcD2LkGU21SUWfqeEAD_Owv6YoJwui0vnA3HDoO4GyFo-7C-nyJR4TcjhiUwbRBCVE&source=FB_Ads&fbclid=IwAR3Iho4uch2202Ori14BDnOqmiftVzcUPCJ6zRd2cO5oXaqBARzLnoh0k5Q')}>
          <Icon name="logo-whatsapp" />
        </Fab> */}
        {this.rodape()}
      </Container>
    );
  }

  rodape() {

    const {
      setCurrentTab
    } = this.props;

    return (
      <Footer style={{ height: 80 }}>
        <FooterTab style={{ backgroundColor: '#fff' }}>
          <Button vertical onPress={() => { setCurrentTab(0); }}>
            <Icon name="home" type="AntDesign" style={{ color: '#4d4d4d', fontSize: 25 }} />
            <Text style={this.props.currentTab.currentTab === 0 ? styles.tabAtivo : styles.tabInativo}>Inicio</Text>
          </Button>
          {Object.entries(this.props.carrinho.carrinho).length > 0 ?
            <Button vertical badge onPress={() => {

              if (this.props.carrinho.logado) {
                setCurrentTab(1);
              } else {
                MAlert('Neste momento precisamos que você se identifique, faça seu login ou cadastro!', 'Login necessário', false);
                this.props.navigation.navigate('Login');
              }

            }}>
              <Badge><Text>{Object.entries(this.props.carrinho.carrinho).length}</Text></Badge>
              <Icon name="shoppingcart" type="AntDesign" style={{ color: '#4d4d4d', fontSize: 25 }} />
              <Text style={this.props.currentTab.currentTab === 1 ? styles.tabAtivo : styles.tabInativo}>Carrinho</Text>
            </Button> :
            <Button vertical onPress={async () => {
              if (this.props.carrinho.logado) {
                setCurrentTab(1);
              } else {
                MAlert('Neste momento precisamos que você se identifique, faça seu login ou cadastro!', 'Login necessário', false);
                this.props.navigation.navigate('Login');
              }
            }}>
              <Icon name="shoppingcart" type="AntDesign" style={{ color: '#4d4d4d', fontSize: 25 }} />
              <Text style={this.props.currentTab.currentTab === 1 ? styles.tabAtivo : styles.tabInativo}>Carrinho</Text>
            </Button>
          }
          {this.props.pedidoA.pedidoA === "1" ?
            <Button badge vertical onPress={() => {

              if (this.props.carrinho.logado) {
                setCurrentTab(2);
              } else {
                MAlert('Neste momento precisamos que você se identifique, faça seu login ou cadastro!', 'Login necessário', false);
                this.props.navigation.navigate('Login');
              }

            }}>
              <Badge ><Text style={{ color: 'red' }}>  </Text></Badge>
              <Icon name="user" type="AntDesign" style={{ color: '#4d4d4d', fontSize: 25 }} />
              <Text style={this.props.currentTab.currentTab === 2 ? styles.tabAtivo : styles.tabInativo}>Conta</Text>
            </Button>
            :
            <Button vertical onPress={() => {
              if (this.props.carrinho.logado) {
                setCurrentTab(2);
              } else {
                MAlert('Neste momento precisamos que você se identifique, faça seu login ou cadastro!', 'Login necessário', false);
                this.props.navigation.navigate('Login');
              }
            }}>
              <Icon name="user" type="AntDesign" style={{ color: '#4d4d4d', fontSize: 25 }} />
              <Text style={this.props.currentTab.currentTab === 2 ? styles.tabAtivo : styles.tabInativo}>Conta</Text>
            </Button>
          }

        </FooterTab>
      </Footer>
    );
  }

  renderJanelas() {
    if (this.props.currentTab.currentTab === 0) {
      return <Home navigation={this.props.navigation} />
    } else if (this.props.currentTab.currentTab === 1) {
      console.log('Carr: ', this.props.carrinho)
      return <Carrinho navigation={this.props.navigation} />
    } else if (this.props.currentTab.currentTab === 2) {
      return <Usuario navigation={this.props.navigation} pedidos={this.props.navigation.getParam('pedidos', '')} />
    } else if (this.props.currentTab.currentTab === 3) {
      // return <Carrinho navigation={this.props.navigation} />
    } else if (this.props.currentTab.currentTab === 4) {
      // return <Usuario navigation={this.props.navigation} />
    }
  }

}


const mapStateToProps = store => ({
  carrinho: store.CarrinhoReducer,
  currentTab: store.CurrentTabReducer,
  pedidoA: store.PedidoAReducer
});

const mapDispatchToProps = dispatch =>
  bindActionCreators({ setCar, getCar, setCarEmp, setCurrentTab, setPedidoA }, dispatch);

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(Main);
