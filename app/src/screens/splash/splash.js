import React, { Component } from 'react';
import { Container, Content, Spinner } from 'native-base';
import { View, Image, StatusBar, ImageBackground, Platform, Text, TouchableOpacity } from 'react-native';
import logo from './../../../assets/logo.png';
import fundo from './../../../assets/fundo.jpeg';
import { openDatabase } from 'react-native-sqlite-storage';
var db = openDatabase({ name: 'delivery.db', createFromLocation: 1 });
import api from './../../utilites/api';
import { MAlert } from './../../utilites/utilites';

import { setPedidoA, SET_LOGADO } from './../../redux/actions';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import OneSignal from 'react-native-onesignal'; // Import package from node modules
     
var tt = ''

// import { Container } from './styles';

class Splash extends Component {

  state = {
    hasPermission: false
  }

  async componentDidMount() {

    Platform.OS === 'ios' ? OneSignal.registerForPushNotifications() : null;

    OneSignal.setLogLevel(6, 0);

    OneSignal.init("afd0af48-4ff6-42a7-9776-10895db36d93", { kOSSettingsKeyAutoPrompt: false, kOSSettingsKeyInAppLaunchURL: false, kOSSettingsKeyInFocusDisplayOption: 2 });
    OneSignal.inFocusDisplaying(2); // Controls what should happen if a notification is received while the app is open. 2 means that the notification will go directly to the device's notification center.

    // The promptForPushNotifications function code will show the iOS push notification prompt. We recommend removing the following code and instead using an In-App Message to prompt for notification permission (See step below)
    //OneSignal.promptForPushNotificationsWithUserResponse(myiOSPromptCallback);

    OneSignal.addEventListener('received', this.onReceived);
    OneSignal.addEventListener('opened', this.onOpened);
    OneSignal.addEventListener('ids', this.onIds);

    try {
      const response = await api.post('ws/StatusServico.php');

      const {
        SET_LOGADO
      } = this.props;

      console.log('resposta2: ', response.data)
      if (response.status === 200) {
        db.transaction(tx => {
          tx.executeSql('SELECT * FROM user', [], (tx, results) => {
            console.log('item:', results.rows.item.senha);
            console.log('Ser:', response.data);
            if (results.rows.length > 0) {
              SET_LOGADO(true)
              this.getServidor(results.rows.item(0).id, response.data.Codigo, results.rows.item(0).nome)
            } else {
              SET_LOGADO(false)
              this.getServidor(0, response.data.Codigo, "")
              // this.props.navigation.replace('Login', {
              //   servico: response.data.Codigo
              // });
            }
          });
        });
      } else {
        MAlert('Erro na comunicação com  servidor!', 'Atenção', false);
      }

    } catch (error) {
      console.log('eroo ', error)
      MAlert('Erro na comunicação com servidor!', 'Atenção', false);
    }

  }
  componentWillUnmount() { }

  async getServidor(id, Codigo, Nome) {
    const token = await tt;
    console.log('token firebase ', token)
    const data = new FormData();
    data.append('token', token);
    data.append('usuario', id);
    data.append('Cliente', id + '-' + Nome);
    const response = await api.post('ws/setToken.php', data);
    const response2 = await api.post('ws/PedidoAberto.php', data);
    console.log('resposta2', response2.data)
    console.log('resposta', response.data)

    const {
      setPedidoA
    } = this.props;

    setPedidoA(response2.data.Codigo);

    this.props.navigation.replace('Inicial', {
      servico: Codigo,
      pedidos: response2.data.Codigo === "1" ? true : false
    });
  }

  getToken = async () => {
    //get the messeging token

  }

  onReceived(notification) {
    //console.log("Notification received: ", notification);
  }

  onOpened(openResult) {
    //console.log('Message: ', openResult.notification.payload.body);
    //console.log('Data: ', openResult.notification.payload.additionalData);
    //console.log('isActive: ', openResult.notification.isAppInFocus);
    //console.log('openResult: ', openResult);
  }

  onIds(device) {
    console.log('Device info: ', device);
    tt = device.userId;
  }

  render() {

    return (
      <Container>
        <ImageBackground source={fundo} style={{ flex: 1, resizeMode: "cover", justifyContent: "center" }}>
          <Content style={{ background: '#fff' }}>

            <StatusBar
              backgroundColor="#fff"
              barStyle="dark-content"
            />
            <View style={{
              justifyContent: 'space-between',
              alignItems: 'center',
              marginTop: '50%',
              width: '100%',
            }}>
              <Image source={logo} style={{
                width: 200,
                height: 200,
              }} />
              <Spinner color='#991e25' style={{ marginTop: 10 }} />

            </View>

          </Content>
        </ImageBackground>
      </Container>
    );
  }
}

const mapStateToProps = store => ({
  pedidoA: store.PedidoAReducer
});

const mapDispatchToProps = dispatch =>
  bindActionCreators({ setPedidoA, SET_LOGADO }, dispatch);

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(Splash);


