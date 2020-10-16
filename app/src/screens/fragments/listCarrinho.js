import React, { Component } from 'react';
import { Text, Icon, Button, Radio, ListItem, Left, Right, CheckBox, Body } from "native-base";
import { View, TouchableOpacity, Alert, Modal } from 'react-native';
import { setDelCar } from './../../redux/actions'
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';

class ListCarrinho extends Component {
  render() {
    return (
      <ListItem style={{ marginLeft: 0 }} style={{ flexDirection: 'row', justifyContent: 'space-between', width: '100%' }}>
        <View style={{ marginLeft: 0, paddingTop: 0, width: 250 }}>
          <Text style={{ width: '100%', }} numberOfLines={1}>{this.props.qtd}x {this.props.nome}</Text>
          <Text style={{ width: '100%', fontSize: 12,}} numberOfLines={3}>{this.props.adcionais}</Text>
          <Text style={{ fontSize: 12, marginTop: 1, width: '100%' }} numberOfLines={1}>
          </Text>
          <Text style={{ fontSize: 14, marginTop: 1, width: '100%', fontWeight:  'bold' }} numberOfLines={1}>R$ {this.mascaraValor(eval(parseFloat(this.props.preco)).toFixed(2))}</Text>
        </View>
        <TouchableOpacity style={{ height: 60, width: 60, alignItems: 'flex-end', marginRight: 0 }}
          onPress={() => {
            try {

              Alert.alert(
                "Atenção",
                "Deseja excluir o item do seu carrinho?",
                [
                  {
                    text: 'Não',
                    onPress: () => { },
                    style: 'cancel',
                  },
                  {
                    text: 'Sim', onPress: async () => {

                      const {
                        setDelCar
                      } = this.props;
                      console.log('car1', this.props.pos)
                      setDelCar(this.props.pos);
                      //console.log('car', this.props.carrinho)
                    }
                  },
                ],
                { cancelable: false },
              );

            } catch (erro) {
              console.log('Erro ', erro)
            }


          }}>
          <Icon type='MaterialCommunityIcons' name='trash-can-outline' style={{ marginTop: 10, marginRight: -5, fontSize: 22, color: 'red' }} />
        </TouchableOpacity>

      </ListItem>
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

const mapStateToProps = state => ({
  carr: state.CarrinhoReducer
});

const mapDispatchToProps = dispatch =>
  bindActionCreators({ setDelCar }, dispatch);

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(ListCarrinho);
