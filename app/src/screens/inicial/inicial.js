import React, { Component } from 'react';
import { Container, Content, Icon, Badge, Text } from 'native-base';
import { View, Image, StatusBar, TouchableOpacity, ImageBackground, Linking } from 'react-native';
import logo from './../../../assets/logo.png';
import fundo from './../../../assets/fundo.jpeg';
// import { Container } from './styles';

import { setPedidoA } from './../../redux/actions';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

class Inicial extends Component {

  async componentDidMount() {
    console.log('Teste', this.props.navigation.getParam('servico', ''))
    console.log('Teste3', this.props.pedidoA.pedidoA)
  }
  render() {
    return (
      <Container>
        <ImageBackground source={fundo} style={{ flex: 1, resizeMode: "cover", justifyContent: "center" }}>
          <Content style={{ background: { fundo } }}>
            <StatusBar
              backgroundColor="#fff"
              barStyle="dark-content"
            />

            <View style={{
              justifyContent: 'space-between',
              alignItems: 'center',
              marginTop: '30%',
              width: '100%',
            }}>
              <Image source={logo} style={{
                width: 200,
                height: 200,
              }} />

              <TouchableOpacity style={{ flexDirection: 'row', marginTop: 30, backgroundColor: "#991e25", width: 190, justifyContent: 'space-between' }}
                onPress={() => {
                  this.props.navigation.navigate('Main',
                    { pedidos: this.props.navigation.getParam('pedidos', '') });
                }}
              >
                <View style={{ height: 40, flexDirection: 'row', alignItems: 'center', width: '100%' }}>
                  <Text style={{ fontSize: 15, color: 'white', width: '100%', textAlign: 'center' }}>Fazer um pedido</Text>
                </View>
              </TouchableOpacity>

              <TouchableOpacity style={{ flexDirection: 'row', marginTop: 10, backgroundColor: "#991e25", width: 190, justifyContent: 'space-between' }}
                onPress={() => {
                  this.props.navigation.navigate('Pedidos',
                    { pedidos: this.props.navigation.getParam('pedidos', '') });
                }}
              >
                <View style={{ height: 40, flexDirection: 'row', alignItems: 'center', width: '100%' }}>
                  <Text style={{ fontSize: 15, color: 'white', width: '100%', textAlign: 'center' }}>Meus pedidos </Text>
                  {this.props.pedidoA.pedidoA === "1" ? <Badge style={{ marginTop: 7, marginLeft: -40 }}><Text>1</Text></Badge> : null}
                </View>
              </TouchableOpacity>

              <View style={{ flexDirection: 'row', marginTop: 50, width: 200, justifyContent: 'space-between' }}>
                <TouchableOpacity style={{ width: 40, height: 40, alignItems: 'center' }} onPress={() => {
                  Linking.openURL('https://instagram.com/sheikburgs?igshid=10liw3jsnja18');
                }}>
                  <Icon name="instagram" type="AntDesign" style={{ fontSize: 40, color: '#991e25' }} />
                </TouchableOpacity>
                <TouchableOpacity style={{ width: 40, height: 40, alignItems: 'center' }} onPress={() => {
                  Linking.openURL('https://api.whatsapp.com/send?phone=559293531070&data=AbvJe2XDlwgR_gYeZ6tt2N-Mh5uAwwMnX8TWOpYC3JDNvvFBjsRZESxXui6at63zzkcD2LkGU21SUWfqeEAD_Owv6YoJwui0vnA3HDoO4GyFo-7C-nyJR4TcjhiUwbRBCVE&source=FB_Ads&fbclid=IwAR3Iho4uch2202Ori14BDnOqmiftVzcUPCJ6zRd2cO5oXaqBARzLnoh0k5Q');
                }}>
                  <Icon name="whatsapp" type="FontAwesome" style={{ fontSize: 40, color: '#991e25' }} />
                </TouchableOpacity>
                <TouchableOpacity style={{ width: 40, height: 40, alignItems: 'center' }} onPress={() => {
                  Linking.openURL('https://www.facebook.com/Sheik-Burgs-Pizzaria-Santa-Etelvina-230666767820778/');
                }}>
                  <Icon name="facebook-square" type="FontAwesome" style={{ fontSize: 40, color: '#991e25' }} />
                </TouchableOpacity>
              </View>


              <View>
                {this.props.navigation.getParam('servico', '') === '5' ?
                  <View style={{ height: 40, flexDirection: 'row', alignItems: 'center', marginTop: 20, width: 190, justifyContent: 'center' }}>
                    <Text style={{ fontSize: 18, color: 'green', fontWeight: 'bold' }}>Estamos abertos</Text>
                  </View>
                  :
                  <View style={{ height: 40, flexDirection: 'row', alignItems: 'center', marginTop: 20, width: 190, justifyContent: 'center' }}>
                    <Text style={{ fontSize: 18, color: 'red', fontWeight: 'bold' }}>Estamos fechado</Text>
                  </View>
                }
              </View>

              {/* <View style={{ flexDirection: 'row', width: 190, justifyContent: 'space-between', marginTop: 25 }}>
                <Text style={{ fontSize: 15, width: '100%', textAlign: 'center', color: '#000' ,fontWeight: 'bold' }}>Aberto de terça a domingo das 18hrs as 23:00hrs</Text>
              </View> */}


            </View>
          </Content>
        </ImageBackground>
      </Container >
    );
  }
}

const mapStateToProps = store => ({
  pedidoA: store.PedidoAReducer
});

const mapDispatchToProps = dispatch =>
  bindActionCreators({ setPedidoA }, dispatch);

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(Inicial);
