<!--
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software: you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with OrangeHRM.
 * If not, see <https://www.gnu.org/licenses/>.
 */
 -->

<template>
  <div v-show="date" class="oxd-table-card-cell">
    <div v-show="showHeader" class="header">
      {{ header.title }}
    </div>
    <div class="data">
      <oxd-text tag="p">
        {{ date }} {{ time }}
        <oxd-text tag="span" class="timezone">
          GMT {{ offset ? offset : '00:00' }}
        </oxd-text>
      </oxd-text>
    </div>
  </div>
</template>

<script>
import {useInjectTableProps} from '@ohrm/oxd';

export default {
  name: 'RecordCell',

  props: {
    header: {
      type: Object,
      required: true,
    },
    date: {
      type: String,
      default: null,
    },
    time: {
      type: String,
      default: null,
    },
    offset: {
      type: String,
      default: null,
    },
  },

  setup() {
    const {screenState} = useInjectTableProps();

    return {
      screenState,
    };
  },

  computed: {
    showHeader() {
      return !(
        this.screenState.screenType === 'lg' ||
        this.screenState.screenType === 'xl'
      );
    },
  },
};
</script>

<style lang="scss" scoped>
.oxd-table-card-cell {
  display: block;
  & .header {
    font-weight: 700;
  }
  & .timezone {
    color: $oxd-interface-gray-color;
    white-space: nowrap;
  }
}
</style>
