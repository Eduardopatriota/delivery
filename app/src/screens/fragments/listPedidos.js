import React, { Component } from 'react';
import { Text, ListItem, Body, Right } from "native-base";
import { View, TouchableOpacity, Modal } from 'react-native';
import { Icon } from 'native-base';

// import { Container } from './styles';

export default class ListPedidos extends Component {

  state = {
    isModal: false
  }


  render() {
    return (
      <ListItem style={{ marginLeft: -0, flexDirection: 'column' }} onPress={() => { this.setState({ isModal: true }) }}>
        <View style={{ width: '100%', flexDirection: 'row', justifyContent: 'space-between' }}>
          <Text style={{}}>{this.props.Data.toString().replace('.', '/').replace('.', '/')}</Text>
          <Text style={{ fontSize: 14 }}>R$ {this.mascaraValor(eval(parseFloat(this.props.Valor)).toFixed(2))}</Text>
        </View>
        <View style={{ width: '100%', }}>
          <Text style={{ width: '100%', }}>{this.props.Status}</Text>
        </View>
        {this.mdItens()}
      </ListItem>
    );
  }

  mdItens() {
    return (
      <Modal
        animationType="fade"
        transparent={true}
        visible={this.state.isModal}
        onRequestClose={() => {
          this.setState({ isModal: false })
        }}>
        <View style={{ flex: 1, marginTop: 0, backgroundColor: 'rgba(0, 0, 0, 0.2)', }}>
          <View style={{ flex: 1, marginTop: 100, backgroundColor: '#ffffff', borderTopRightRadius: 0, borderTopLeftRadius: 0 }}>
            <View style={{ marginTop: 10, marginLeft: 25 }}>
              <TouchableOpacity style={{ height: 40, width: 40 }} onPress={() => { this.setState({ isModal: false }) }}>
                <Icon name="close" />
              </TouchableOpacity>
            </View>
            <View><Text style={{ fontSize: 12, fontWeight: 'bold', marginLeft: 15, marginBottom: 15 }}>Itens: </Text></View>
            <View style={{width: '95%'}}>
              {this.props.Itens.map((i, ix) => (
                <ListItem>
                  <Body>
                    <Text>{i.Produto} x {i.Quantidade}</Text>
                  </Body>
                  <Right>
                    <Text>R$ {this.mascaraValor(eval(parseFloat(i.Valor)).toFixed(2))}</Text>
                  </Right>
                </ListItem>
              ))}
            </View>
          </View>
        </View>
      </Modal >
    );
  }

  mascaraValor(valor) {
    valor = valor.toString().replace(/\D/g, "");
    valor = valor.toString().replace(/(\d)(\d{8})$/, "$1.$2");
    valor = valor.toString().replace(/(\d)(\d{5})$/, "$1.$2");
    valor = valor.toString().replace(/(\d)(\d{2})$/, "$1,$2");
    return valor
  }
}
