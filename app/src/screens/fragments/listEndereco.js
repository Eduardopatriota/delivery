import React, { Component } from 'react';
import { Text, ListItem } from "native-base";
import { View, TouchableOpacity } from 'react-native';
import { Icon } from 'native-base';

export default class ListEnderecos extends Component {

  render() {
    return (
      <ListItem style={{marginLeft: -0}}>
        <TouchableOpacity style={{ flexDirection: 'row', justifyContent: 'space-between' }} onPress={this.props.Press}>
          <View>
            <Text style={{ width: '100%', }}>{this.props.Nome}</Text>
            <Text style={{ width: '100%', marginTop: 5, fontSize: 14 }}>{this.props.Endereco}</Text>
          </View>
          <TouchableOpacity style={{ marginRight: 5 }} onPress={this.props.Press}>
            {this.props.idDefault ? <Icon style={{ color: 'green' }} type="MaterialIcons" name="radio-button-checked" /> :
              null}
          </TouchableOpacity>
        </TouchableOpacity>
      </ListItem>
    );
  }
}