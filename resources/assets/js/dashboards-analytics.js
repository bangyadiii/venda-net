/**
 * Dashboard Analytics
 */

'use strict';

let cardColor, headingColor, axisColor, shadeColor, borderColor;
cardColor = config.colors.cardColor;
headingColor = config.colors.headingColor;
axisColor = config.colors.axisColor;
borderColor = config.colors.borderColor;

const cpuChartOption = {
  series: [0],
  labels: ['CPU'],
  chart: {
    height: 210,
    type: 'radialBar'
  },
  plotOptions: {
    radialBar: {
      size: 80,
      offsetY: 9,
      startAngle: -150,
      endAngle: 150,
      hollow: {
        size: '55%'
      },
      track: {
        strokeWidth: '100%'
      },
      dataLabels: {
        name: {
          offsetY: 15,
          color: headingColor,
          fontSize: '15px',
          fontWeight: '500',
          fontFamily: 'Public Sans'
        },
        value: {
          offsetY: -25,
          color: headingColor,
          fontSize: '22px',
          fontWeight: '500',
          fontFamily: 'Public Sans'
        }
      }
    }
  },
  colors: [config.colors.primary],
  fill: {
    type: 'gradient',
    gradient: {
      shade: 'dark',
      shadeIntensity: 0.5,
      gradientToColors: [config.colors.primary],
      inverseColors: false,
      opacityFrom: 1,
      opacityTo: 0.6,
      stops: [30, 70, 100]
    }
  },
  stroke: {
    dashArray: 5
  },
  grid: {
    padding: {
      top: -35,
      bottom: -10
    }
  },
  states: {
    hover: {
      filter: {
        type: 'none'
      }
    },
    active: {
      filter: {
        type: 'none'
      }
    }
  }
};

const memoryChartOption = {
  ...cpuChartOption,
  series: [0],
  labels: ['Memory']
};
